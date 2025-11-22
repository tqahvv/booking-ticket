<?php

namespace Database\Seeders;

use App\Models\VehicleSeatTemplate;
use App\Models\VehicleType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehicleSeatTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seed16Seats();
        $this->seed29Seats();
        $this->seed45Seats();
        $this->seedSleeper34();
        $this->seedSleeper40();
    }

    private function seed16Seats()
    {
        $vehicle = VehicleType::where('name', 'Ghế ngồi 16 chỗ')->first();
        if (!$vehicle) return;

        $rows = 4;
        $cols = 4;
        $driverPosition = ['row' => 1, 'column' => 1];

        foreach (range(1, $rows) as $r) {
            foreach (range(1, $cols) as $c) {
                $seatType = ($r == $driverPosition['row'] && $c == $driverPosition['column'])
                    ? 'driver'
                    : 'seat';

                VehicleSeatTemplate::create([
                    'vehicle_type_id' => $vehicle->id,
                    'seat_code' => "R{$r}C{$c}",
                    'row' => $r,
                    'column' => $c,
                    'seat_type' => $seatType,
                    'deck' => 1,
                ]);
            }
        }
    }

    private function seed29Seats()
    {
        $vehicle = VehicleType::where('name', 'Ghế ngồi 29 chỗ')->first();
        if (!$vehicle) return;

        $rows = 6;
        $cols = 5;
        $driverPosition = ['row' => 1, 'column' => 1];

        foreach (range(1, $rows) as $r) {
            foreach (range(1, $cols) as $c) {
                $seatType = ($r == $driverPosition['row'] && $c == $driverPosition['column'])
                    ? 'driver'
                    : 'seat';

                VehicleSeatTemplate::create([
                    'vehicle_type_id' => $vehicle->id,
                    'seat_code' => "R{$r}C{$c}",
                    'row' => $r,
                    'column' => $c,
                    'seat_type' => $seatType,
                    'deck' => 1,
                ]);
            }
        }
    }

    private function seed45Seats()
    {
        $vehicle = VehicleType::where('name', 'Ghế ngồi 45 chỗ')->first();
        if (!$vehicle) return;

        $rows = 9;
        $cols = 5;
        $driverPosition = ['row' => 1, 'column' => 1];

        foreach (range(1, $rows) as $r) {
            foreach (range(1, $cols) as $c) {
                $seatType = ($r == $driverPosition['row'] && $c == $driverPosition['column'])
                    ? 'driver'
                    : 'seat';

                VehicleSeatTemplate::create([
                    'vehicle_type_id' => $vehicle->id,
                    'seat_code' => "R{$r}C{$c}",
                    'row' => $r,
                    'column' => $c,
                    'seat_type' => $seatType,
                    'deck' => 1,
                ]);
            }
        }
    }

    private function seedSleeper34()
    {
        $vehicle = VehicleType::where('name', 'Giường nằm 34 chỗ')->first();
        if (!$vehicle) return;

        $rowsPerDeck = [8, 9];
        $cols = 2;

        $count = 1;
        foreach (range(1, $rowsPerDeck[0]) as $r) {
            foreach (range(1, $cols) as $c) {
                VehicleSeatTemplate::create([
                    'vehicle_type_id' => $vehicle->id,
                    'seat_code' => "D{$count}",
                    'row' => $r,
                    'column' => $c,
                    'seat_type' => 'seat',
                    'deck' => 1,
                ]);
                $count++;
            }
        }

        $count = 1;
        foreach (range(1, $rowsPerDeck[1]) as $r) {
            foreach (range(1, $cols) as $c) {
                VehicleSeatTemplate::create([
                    'vehicle_type_id' => $vehicle->id,
                    'seat_code' => "U{$count}",
                    'row' => $r,
                    'column' => $c,
                    'seat_type' => 'seat',
                    'deck' => 2,
                ]);
                $count++;
            }
        }
    }

    private function seedSleeper40()
    {
        $vehicle = VehicleType::where('name', 'Giường nằm 40 chỗ')->first();
        if (!$vehicle) return;

        $rowsPerDeck = 10;
        $cols = 2;

        $count = 1;
        foreach (range(1, $rowsPerDeck) as $r) {
            foreach (range(1, $cols) as $c) {
                VehicleSeatTemplate::create([
                    'vehicle_type_id' => $vehicle->id,
                    'seat_code' => "D{$count}",
                    'row' => $r,
                    'column' => $c,
                    'seat_type' => 'seat',
                    'deck' => 1,
                ]);
                $count++;
            }
        }

        $count = 1;
        foreach (range(1, $rowsPerDeck) as $r) {
            foreach (range(1, $cols) as $c) {
                VehicleSeatTemplate::create([
                    'vehicle_type_id' => $vehicle->id,
                    'seat_code' => "U{$count}",
                    'row' => $r,
                    'column' => $c,
                    'seat_type' => 'seat',
                    'deck' => 2,
                ]);
                $count++;
            }
        }
    }
}
