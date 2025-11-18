<?php

namespace Database\Seeders;

use App\Models\VehicleType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehicleTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        VehicleType::insert([
            ['name' => 'Ghế ngồi 29 chỗ', 'capacity_total' => 29, 'description' => 'Xe ghế ngồi tiêu chuẩn'],
            ['name' => 'Giường nằm 40 chỗ', 'capacity_total' => 40, 'description' => 'Xe giường nằm chất lượng cao'],
            ['name' => 'Limousine 9 chỗ', 'capacity_total' => 9, 'description' => 'Xe VIP Limousine'],
            ['name' => 'Ghế ngồi 45 chỗ', 'capacity_total' => 45, 'description' => 'Xe ghế ngồi đường dài'],
            ['name' => 'Giường nằm 34 chỗ', 'capacity_total' => 34, 'description' => 'Xe giường nằm cao cấp'],
        ]);
    }
}
