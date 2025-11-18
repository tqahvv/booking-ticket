<?php

namespace Database\Seeders;

use App\Models\Amenity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AmenitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Amenity::insert([
            ['name' => 'Wi-Fi', 'description' => 'Wi-Fi miễn phí', 'icon' => 'wifi'],
            ['name' => 'Ổ cắm sạc', 'description' => 'Ổ cắm sạc', 'icon' => 'plug'],
            ['name' => 'Máy lạnh', 'description' => 'Máy lạnh', 'icon' => 'snowflake'],
            ['name' => 'Nước uống', 'description' => 'Nước uống', 'icon' => 'bottle'],
            ['name' => 'TV giải trí', 'description' => 'TV giải trí', 'icon' => 'tv'],
        ]);
    }
}
