<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MarketPrice;

class ImportMarketPrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-market-prices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import market prices from a tab-separated text file into the database.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $data = [
            ["NPK 17 17 17", "", "0.00 Ugx", "0.00 Ugx", "0.00 Ugx", "0.00%"],
            ["Maize Grain", "kg", "2,000.00 Ugx", "1,600.00 Ugx", "400.00 Ugx", "25.00%"],
            ["Maize Flour", "kg", "2,700.00 Ugx", "2,200.00 Ugx", "500.00 Ugx", "22.73%"],
            ["Beans (K132)", "kg", "4,500.00 Ugx", "3,800.00 Ugx", "700.00 Ugx", "18.42%"],
            ["Beans Rosecoco", "kg", "4,500.00 Ugx", "3,800.00 Ugx", "700.00 Ugx", "18.42%"],
            ["Yellow Beans", "kg", "4,700.00 Ugx", "4,200.00 Ugx", "500.00 Ugx", "11.90%"],
            ["Millet grain", "kg", "3,600.00 Ugx", "3,400.00 Ugx", "200.00 Ugx", "5.88%"],
            ["Millet flour", "kg", "4,000.00 Ugx", "3,500.00 Ugx", "500.00 Ugx", "14.29%"],
            ["Sorghum grain", "kg", "1,400.00 Ugx", "1,300.00 Ugx", "100.00 Ugx", "7.69%"],
            ["Sorghum flour", "kg", "3,200.00 Ugx", "2,800.00 Ugx", "400.00 Ugx", "14.29%"],
            ["Rice (super)", "kg", "4,500.00 Ugx", "3,800.00 Ugx", "700.00 Ugx", "18.42%"],
            ["Cassava Chips", "kg", "600.00 Ugx", "500.00 Ugx", "100.00 Ugx", "20.00%"],
            ["Cassava Flour", "kg", "2,000.00 Ugx", "1,500.00 Ugx", "500.00 Ugx", "33.33%"],
            ["Groundnuts", "kg", "7,000.00 Ugx", "5,500.00 Ugx", "1,500.00 Ugx", "27.27%"],
            ["Simsim", "kg", "7,500.00 Ugx", "6,000.00 Ugx", "1,500.00 Ugx", "25.00%"],
            ["Soya beans", "kg", "3,500.00 Ugx", "3,200.00 Ugx", "300.00 Ugx", "9.38%"],
            ["Ginger", "kg", "5,000.00 Ugx", "4,000.00 Ugx", "1,000.00 Ugx", "25.00%"],
            ["Fish Tilapia", "kg", "15,000.00 Ugx", "10,000.00 Ugx", "5,000.00 Ugx", "50.00%"],
            ["Fish Nile Perch", "kg", "18,000.00 Ugx", "12,000.00 Ugx", "6,000.00 Ugx", "50.00%"],
            ["Fresh Cassava", "kg", "600.00 Ugx", "500.00 Ugx", "100.00 Ugx", "20.00%"],
            ["Sweet Potatoes", "kg", "600.00 Ugx", "500.00 Ugx", "100.00 Ugx", "20.00%"],
            ["Irish Potatoes", "kg", "2,500.00 Ugx", "1,600.00 Ugx", "900.00 Ugx", "56.25%"],
            ["Matoke/Banana", "Bunch", "1,521.00 Ugx", "1,174.00 Ugx", "347.00 Ugx", "29.56%"],
            ["Milk", "Lt", "2,000.00 Ugx", "1,500.00 Ugx", "500.00 Ugx", "33.33%"],
            ["Pumpkin", "kg", "4,000.00 Ugx", "3,000.00 Ugx", "1,000.00 Ugx", "33.33%"],
            ["Tomatoes", "kg", "3,000.00 Ugx", "2,500.00 Ugx", "500.00 Ugx", "20.00%"],
            ["Nakati vegetables", "kg", "2,200.00 Ugx", "1,800.00 Ugx", "400.00 Ugx", "22.22%"],
            ["Bbuuga vegetables", "kg", "1,800.00 Ugx", "1,500.00 Ugx", "300.00 Ugx", "20.00%"],
            ["DAP", "kg", "4,500.00 Ugx", "3,500.00 Ugx", "1,000.00 Ugx", "28.57%"],
            ["Urea", "kg", "3,200.00 Ugx", "2,600.00 Ugx", "600.00 Ugx", "23.08%"],
        ];
        $count = 0;
        foreach ($data as $fields) {
            [$commodity, $unit, $retail_price, $wholesale_price, $difference, $change_percentage] = $fields;
            MarketPrice::updateOrCreate(
                ['commodity' => $commodity],
                [
                    'unit' => $unit,
                    'retail_price' => $retail_price,
                    'wholesale_price' => $wholesale_price,
                    'difference' => $difference,
                    'change_percentage' => $change_percentage,
                ]
            );
            $count++;
        }
        $this->info("Imported/updated $count market prices.");
        return 0;
    }
}
