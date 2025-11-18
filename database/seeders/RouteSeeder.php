<?php

namespace Database\Seeders;

use App\Models\Route;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RouteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Route::insert([
            ['operator_id' => 6, 'origin_location_id' => 6, 'destination_location_id' => 1, 'distance' => 350, 'description' => 'Tuyến Vinh - Hà Nội'],
            ['operator_id' => 7, 'origin_location_id' => 7, 'destination_location_id' => 2, 'distance' => 340, 'description' => 'Tuyến Vinh - Mỹ Đình'],
            ['operator_id' => 8, 'origin_location_id' => 6, 'destination_location_id' => 3, 'distance' => 330, 'description' => 'Tuyến Vinh - Nước Ngầm'],
            ['operator_id' => 1, 'origin_location_id' => 1, 'destination_location_id' => 5, 'distance' => 1700, 'description' => 'Hà Nội - Hồ Chí Minh'],
            ['operator_id' => 2, 'origin_location_id' => 5, 'destination_location_id' => 4, 'distance' => 950, 'description' => 'Sài Gòn - Đà Nẵng'],
        ]);
    }
}
