<?php

namespace Database\Seeders;

use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schedule::insert([
            ['operator_id' => 6, 'route_id' => 1, 'vehicle_type_id' => 2, 'departure_datetime' => Carbon::now()->addDays(1), 'arrival_datetime' => Carbon::now()->addDays(1)->addHours(7), 'total_seats' => 40, 'seats_available' => 40, 'base_fare' => 250000, 'status' => 'scheduled'],
            ['operator_id' => 7, 'route_id' => 2, 'vehicle_type_id' => 1, 'departure_datetime' => Carbon::now()->addDays(1)->addHours(5), 'arrival_datetime' => Carbon::now()->addDays(1)->addHours(12), 'total_seats' => 45, 'seats_available' => 45, 'base_fare' => 220000, 'status' => 'scheduled'],
            ['operator_id' => 8, 'route_id' => 3, 'vehicle_type_id' => 3, 'departure_datetime' => Carbon::now()->addDays(2), 'arrival_datetime' => Carbon::now()->addDays(2)->addHours(8), 'total_seats' => 36, 'seats_available' => 36, 'base_fare' => 230000, 'status' => 'scheduled'],
            ['operator_id' => 1, 'route_id' => 4, 'vehicle_type_id' => 4, 'departure_datetime' => Carbon::now()->addDays(3), 'arrival_datetime' => Carbon::now()->addDays(4), 'total_seats' => 50, 'seats_available' => 50, 'base_fare' => 900000, 'status' => 'scheduled'],
            ['operator_id' => 2, 'route_id' => 5, 'vehicle_type_id' => 5, 'departure_datetime' => Carbon::now()->addDays(1)->addHours(3), 'arrival_datetime' => Carbon::now()->addDays(2)->addHours(5), 'total_seats' => 42, 'seats_available' => 42, 'base_fare' => 600000, 'status' => 'scheduled'],
        ]);
    }
}
