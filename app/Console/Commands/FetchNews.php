<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\News;
use Carbon\Carbon;

class FetchNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-news';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch agriculture and farming news from newsdata.io API for East African countries.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $apiKey = 'pub_8fed219455a74316a41e2a35e3e99349';
        $baseUrl = 'https://newsdata.io/api/1/latest';
        
        $this->info('Fetching agriculture and farming news for East African countries...');
        
        $totalImported = 0;
        $eastAfricanCountries = ['Uganda', 'Kenya', 'Tanzania', 'Ethiopia', 'Rwanda', 'Burundi', 'South Sudan'];
        
        foreach ($eastAfricanCountries as $country) {
            $this->info("Fetching agriculture news mentioning $country...");
            
            $response = Http::get($baseUrl, [
                'apikey' => $apiKey,
                'q' => "($country) AND (agriculture OR farming OR crops OR livestock OR food OR harvest)",
                'language' => 'en',
                'size' => 5
            ]);
            
            if (!$response->ok()) {
                $this->warn("Failed to fetch news for $country: " . $response->body());
                continue;
            }
            
            $data = $response->json();
            
            if (!isset($data['results']) || empty($data['results'])) {
                $this->warn("No agriculture news found mentioning $country.");
                continue;
            }
            
            $countryImported = $this->processArticles($data['results'], $country);
            $totalImported += $countryImported;
            
            // Small delay to avoid rate limiting
            sleep(1);
        }
        
        // Also fetch general East Africa agriculture news
        $this->info("Fetching general East Africa agriculture news...");
        $response = Http::get($baseUrl, [
            'apikey' => $apiKey,
            'q' => '(East Africa OR Eastern Africa) AND (agriculture OR farming OR crops OR livestock)',
            'language' => 'en',
            'size' => 10
        ]);
        
        if ($response->ok()) {
            $data = $response->json();
            if (isset($data['results']) && !empty($data['results'])) {
                $generalImported = $this->processArticles($data['results'], 'East Africa');
                $totalImported += $generalImported;
            }
        }
        
        $this->info("Total imported: $totalImported relevant agriculture/farming articles.");

        
        $this->info('News fetching completed successfully.');
        return 0;
    }
    
    private function processArticles($articles, $country)
    {
        $count = 0;
        $totalArticles = count($articles);
        $this->info("Found $totalArticles articles for $country");

        // East African countries - codes and full names
        $targetCountries = [
            'ug', 'uganda', 'ke', 'kenya', 'tz', 'tanzania', 'rw', 'rwanda', 'et', 'ethiopia', 
            'bi', 'burundi', 'ss', 'south sudan', 'east africa', 'eastern africa'
        ];
        $mainKeywords = [
            'agriculture', 'farming', 'livestock', 'crop', 'farmer', 'dairy', 'poultry', 'food', 
            'harvest', 'irrigation', 'fertilizer', 'pesticide', 'organic', 'maize', 'corn', 
            'wheat', 'rice', 'vegetables', 'fruits', 'coffee', 'tea', 'cassava', 'beans', 
            'sorghum', 'millet', 'bananas', 'plantain', 'cattle', 'goats', 'sheep', 'chicken'
        ];

        foreach ($articles as $article) {
            // Check if article already exists
            $existingNews = \App\Models\News::where('url', $article['link'])->first();
            if ($existingNews) {
                $this->info("Skipping existing article: " . ($article['title'] ?? 'No title'));
                continue;
            }

            // Check published date (must be within last 30 days)
            $pubDate = isset($article['pubDate']) ? Carbon::parse($article['pubDate']) : null;
            if (!$pubDate || $pubDate->lt(Carbon::now()->subDays(30))) {
                $this->warn("✗ Skipped: " . ($article['title'] ?? 'No title') . " (too old: " . ($pubDate ? $pubDate->toDateString() : 'no date') . ")");
                continue;
            }

            // Check country code or name
            $articleCountries = [];
            if (isset($article['country'])) {
                $articleCountries = is_array($article['country']) ? $article['country'] : [$article['country']];
                $articleCountries = array_map('trim', explode(',', strtolower(implode(',', $articleCountries))));
            }
            $countryMatch = count(array_intersect($targetCountries, $articleCountries)) > 0;

            // Stricter keyword check (title or description)
            $title = strtolower($article['title'] ?? '');
            $description = strtolower($article['description'] ?? '');
            $keywordMatch = false;
            $matchedKeyword = '';
            foreach ($mainKeywords as $keyword) {
                if (strpos($title, $keyword) !== false || strpos($description, $keyword) !== false) {
                    $keywordMatch = true;
                    $matchedKeyword = $keyword;
                    break;
                }
            }

            // Prioritize articles with both country and keyword matches, but accept either
            if (($countryMatch && $keywordMatch) || ($keywordMatch && count($articleCountries) == 0)) {
                \App\Models\News::create([
                    'title' => $article['title'] ?? '',
                    'description' => $article['description'] ?? '',
                    'content' => $article['content'] ?? '',
                    'url' => $article['link'] ?? '',
                    'image_url' => $article['image_url'] ?? null,
                    'published_date' => $article['pubDate'] ?? now(),
                    'source' => $article['source_id'] ?? ($article['source_name'] ?? ''),
                    'country' => isset($article['country']) ? (is_array($article['country']) ? implode(',', $article['country']) : $article['country']) : null,
                    'language' => $article['language'] ?? 'en',
                    'category' => isset($article['category']) ? (is_array($article['category']) ? implode(',', $article['category']) : $article['category']) : 'agriculture',
                ]);
                $this->info("✓ Imported: " . ($article['title'] ?? 'No title') . " (matched: $matchedKeyword, country: " . implode(',', $articleCountries) . ")");
                $count++;
            } else {
                $reason = !$countryMatch ? 'country mismatch' : 'no agriculture keyword';
                $this->warn("✗ Skipped: " . ($article['title'] ?? 'No title') . " ($reason, country: " . implode(',', $articleCountries) . ")");
            }
        }
        return $count;
    }
}
