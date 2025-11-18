<?php

namespace Database\Seeders;

use App\Models\Stop;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Stop::insert([
            ['route_id' => 1, 'location_id' => 6, 'sequence_no' => 1, 'is_pickup' => true, 'is_dropoff' => false],
            ['route_id' => 1, 'location_id' => 1, 'sequence_no' => 2, 'is_pickup' => false, 'is_dropoff' => true],
            ['route_id' => 2, 'location_id' => 7, 'sequence_no' => 1, 'is_pickup' => true, 'is_dropoff' => false],
            ['route_id' => 2, 'location_id' => 2, 'sequence_no' => 2, 'is_pickup' => false, 'is_dropoff' => true],
            ['route_id' => 3, 'location_id' => 6, 'sequence_no' => 1, 'is_pickup' => true, 'is_dropoff' => false],
            ['route_id' => 3, 'location_id' => 3, 'sequence_no' => 2, 'is_pickup' => false, 'is_dropoff' => true],
        ]);
    }
}
