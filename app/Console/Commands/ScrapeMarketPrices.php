<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;
use App\Models\MarketPrice;

class ScrapeMarketPrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:scrape-market-prices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape crop market prices from Farmgain Africa and update the database.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $url = 'https://farmgainafrica.org/markets/mukono-market'; // Updated to Mukono Market page
        $this->info('Fetching market prices from: ' . $url);

        $response = Http::get($url);
        if (!$response->ok()) {
            $this->error('Failed to fetch the page.');
            return 1;
        }

        $html = $response->body();
        $crawler = new Crawler($html);

        $tableRows = $crawler->filter('table.commodity-price-table tr');
        $this->info('Found ' . $tableRows->count() . ' table rows.');

        $tableRows->each(function (Crawler $row, $i) {
            if ($i === 0) return; // Skip header row
            $columns = $row->filter('td');
            if ($columns->count() < 6) return; // Skip incomplete rows

            $commodity = trim($columns->eq(0)->text());
            $unit = trim($columns->eq(1)->text());
            $retail_price = trim($columns->eq(2)->text());
            $wholesale_price = trim($columns->eq(3)->text());
            $difference = trim($columns->eq(4)->text());
            $change_percentage = trim($columns->eq(5)->text());

            $this->info("Parsed: $commodity | $unit | $retail_price | $wholesale_price | $difference | $change_percentage");

            if (!$commodity) return;

            \App\Models\MarketPrice::updateOrCreate(
                ['commodity' => $commodity],
                [
                    'unit' => $unit,
                    'retail_price' => $retail_price,
                    'wholesale_price' => $wholesale_price,
                    'difference' => $difference,
                    'change_percentage' => $change_percentage,
                ]
            );
        });

        $this->info('Market prices updated successfully.');
        return 0;
    }
}
