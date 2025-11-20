<?php

namespace Database\Seeders;

use App\Models\ScheduleTemplate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ScheduleTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ScheduleTemplate::create([
            'route_id'                => 1,
            'operator_id'             => 1,
            'vehicle_type_id'         => 1,
            'departure_time'          => '08:00',
            'travel_duration_minutes' => 240,
            'running_days'            => [1, 2, 3, 4, 5, 6, 7],
            'start_date'              => now()->toDateString(),
            'end_date'                => null,
            'base_fare'               => 150000,
        ]);

        ScheduleTemplate::create([
            'route_id'                => 2,
            'operator_id'             => 1,
            'vehicle_type_id'         => 2,
            'departure_time'          => '13:00',
            'travel_duration_minutes' => 360,
            'running_days'            => [1, 3, 5],
            'start_date'              => now()->toDateString(),
            'end_date'                => null,
            'base_fare'               => 250000,
        ]);

        ScheduleTemplate::create([
            'route_id'                => 3,
            'operator_id'             => 2,
            'vehicle_type_id'         => 1,
            'departure_time'          => '20:00',
            'travel_duration_minutes' => 420,
            'running_days'            => [6, 7],
            'start_date'              => now()->toDateString(),
            'end_date'                => null,
            'base_fare'               => 300000,
        ]);
    }
}
