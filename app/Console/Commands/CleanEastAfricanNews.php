<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\News;

class CleanEastAfricanNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'news:clean-east-africa';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all news articles not from East African countries (ug, ke, tz, rw, et)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $eastAfrica = ['ug', 'ke', 'tz', 'rw', 'et'];
        $count = 0;
        $all = News::all();
        foreach ($all as $n) {
            $countries = array_map('trim', explode(',', strtolower($n->country ?? '')));
            if (count(array_intersect($eastAfrica, $countries)) === 0) {
                $n->delete();
                $count++;
            }
        }
        $this->info("Deleted $count non-East African news articles.");
        return 0;
    }
} 