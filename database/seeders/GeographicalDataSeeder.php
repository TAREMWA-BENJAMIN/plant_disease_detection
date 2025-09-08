<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Region;
use App\Models\District;

class GeographicalDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Set all users' district_id to NULL to avoid FK constraint
        \DB::table('users')->update(['district_id' => null]);
        // Disable foreign key checks
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        District::truncate();
        Region::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Define regions and their districts
        $regions = [
            'Central' => [
                'Buikwe', 'Bukomansimbi', 'Butambala', 'Buvuma', 'Gomba', 'Kalangala', 'Kalungu', 'Kampala', 'Kasanda', 'Kayunga', 'Kiboga', 'Kyankwanzi', 'Kyotera', 'Luwero', 'Lwengo', 'Lyantonde', 'Masaka', 'Mityana', 'Mpigi', 'Mubende', 'Mukono', 'Nakaseke', 'Nakasongola', 'Rakai', 'Sembabule', 'Wakiso',
            ],
            'Eastern' => [
                'Amuria', 'Budaka', 'Bududa', 'Bugiri', 'Bugweri', 'Bukedea', 'Bukwo', 'Bulambuli', 'Busia', 'Butaleja', 'Butebo', 'Buyende', 'Iganga', 'Jinja', 'Kapelebyong', 'Kaliro', 'Kamuli', 'Kapchorwa', 'Katakwi', 'Kibuku', 'Kumi', 'Kween', 'Luuka', 'Manafwa', 'Mayuge', 'Mbale', 'Namayingo', 'Namisindwa', 'Namutumba', 'Ngora', 'Pallisa', 'Serere', 'Sironko', 'Soroti', 'Tororo',
            ],
            'Northern' => [
                'Abim', 'Adjumani', 'Agago', 'Alebtong', 'Amolatar', 'Amudat', 'Amuru', 'Apac', 'Arua', 'Dokolo', 'Gulu', 'Kaabong', 'Kitgum', 'Koboko', 'Kole', 'Kotido', 'Kwania', 'Lamwo', 'Lira', 'Madi Okollo', 'Maracha', 'Moroto', 'Moyo', 'Nabilatuk', 'Nakapiripirit', 'Napak', 'Nebbi', 'Nwoya', 'Omoro', 'Otuke', 'Oyam', 'Pader', 'Pakwach', 'Terego', 'Yumbe', 'Zombo',
            ],
            'Western' => [
                'Buhweju', 'Buliisa', 'Bundibugyo', 'Bunyangabu', 'Bushenyi', 'Hoima', 'Ibanda', 'Isingiro', 'Kabale', 'Kabarole', 'Kagadi', 'Kakumiro', 'Kamwenge', 'Kanungu', 'Kasese', 'Kazo', 'Kibaale', 'Kibale', 'Kibingo', 'Kiruhura', 'Kiryandongo', 'Kisoro', 'Kitagwenda', 'Kyegegwa', 'Kyenjojo', 'Masindi', 'Mbarara', 'Mitooma', 'Ntoroko', 'Ntungamo', 'Rubanda', 'Rubirizi', 'Rukiga', 'Rukungiri', 'Rwampara', 'Sheema',
            ],
        ];

        // Create regions and districts
        foreach ($regions as $regionName => $districts) {
            $region = Region::create(['name' => $regionName, 'flag' => true]);
            echo "Created region: {$region->name}\n";
            foreach ($districts as $districtName) {
                $district = District::create([
                    'name' => $districtName,
                    'region_id' => $region->id,
                    'flag' => true,
                ]);
                echo "  - Created district: {$district->name} (Region: {$region->name})\n";
            }
        }
    }
} 