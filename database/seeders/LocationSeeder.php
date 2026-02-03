<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = [
            [
                'code' => 'JKT-HO',
                'name' => 'Jakarta Head Office',
                'city' => 'Jakarta',
                'address' => 'Jl. Sudirman No. 123, Jakarta Pusat',
                'latitude' => -6.2088,
                'longitude' => 106.8456,
                'is_active' => true,
            ],
            [
                'code' => 'JKT-WH',
                'name' => 'Jakarta Warehouse',
                'city' => 'Jakarta',
                'address' => 'Jl. Industri No. 45, Jakarta Utara',
                'latitude' => -6.1385,
                'longitude' => 106.8636,
                'is_active' => true,
            ],
            [
                'code' => 'BDG-BR',
                'name' => 'Bandung Branch',
                'city' => 'Bandung',
                'address' => 'Jl. Asia Afrika No. 67, Bandung',
                'latitude' => -6.9175,
                'longitude' => 107.6191,
                'is_active' => true,
            ],
            [
                'code' => 'SBY-BR',
                'name' => 'Surabaya Branch',
                'city' => 'Surabaya',
                'address' => 'Jl. Tunjungan No. 89, Surabaya',
                'latitude' => -7.2575,
                'longitude' => 112.7521,
                'is_active' => true,
            ],
            [
                'code' => 'SMG-BR',
                'name' => 'Semarang Branch',
                'city' => 'Semarang',
                'address' => 'Jl. Pemuda No. 34, Semarang',
                'latitude' => -6.9667,
                'longitude' => 110.4167,
                'is_active' => true,
            ],
            [
                'code' => 'YGY-BR',
                'name' => 'Yogyakarta Branch',
                'city' => 'Yogyakarta',
                'address' => 'Jl. Malioboro No. 56, Yogyakarta',
                'latitude' => -7.7956,
                'longitude' => 110.3695,
                'is_active' => true,
            ],
            [
                'code' => 'MDN-BR',
                'name' => 'Medan Branch',
                'city' => 'Medan',
                'address' => 'Jl. Gatot Subroto No. 78, Medan',
                'latitude' => 3.5952,
                'longitude' => 98.6722,
                'is_active' => true,
            ],
            [
                'code' => 'MKS-BR',
                'name' => 'Makassar Branch',
                'city' => 'Makassar',
                'address' => 'Jl. Ahmad Yani No. 90, Makassar',
                'latitude' => -5.1477,
                'longitude' => 119.4327,
                'is_active' => true,
            ],
            [
                'code' => 'BLI-BR',
                'name' => 'Bali Branch',
                'city' => 'Denpasar',
                'address' => 'Jl. Sunset Road No. 12, Denpasar',
                'latitude' => -8.6705,
                'longitude' => 115.2126,
                'is_active' => true,
            ],
            [
                'code' => 'BKS-WH',
                'name' => 'Bekasi Warehouse',
                'city' => 'Bekasi',
                'address' => 'Jl. Raya Narogong KM 15, Bekasi',
                'latitude' => -6.2383,
                'longitude' => 106.9756,
                'is_active' => true,
            ],
        ];

        foreach ($locations as $location) {
            Location::create($location);
        }

        $this->command->info('Locations seeded successfully!');
    }
}
