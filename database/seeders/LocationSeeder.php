<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Location::insert([
            ['name' => 'Bến xe Giáp Bát', 'city' => 'Hà Nội', 'province' => 'Hà Nội', 'address' => 'Giải Phóng, Hoàng Mai'],
            ['name' => 'Bến xe Mỹ Đình', 'city' => 'Hà Nội', 'province' => 'Hà Nội', 'address' => 'Mỹ Đình, Nam Từ Liêm'],
            ['name' => 'Bến xe Nước Ngầm', 'city' => 'Hà Nội', 'province' => 'Hà Nội', 'address' => 'Km 8 Giải Phóng'],
            ['name' => 'Bến xe Đà Nẵng', 'city' => 'Đà Nẵng', 'province' => 'Đà Nẵng', 'address' => '185 Tôn Đức Thắng'],
            ['name' => 'Bến xe Miền Đông', 'city' => 'Hồ Chí Minh', 'province' => 'Hồ Chí Minh', 'address' => '292 Đinh Bộ Lĩnh'],
            ['name' => 'Bến xe phía Đông', 'city' => 'Vinh', 'province' => 'Nghệ An', 'address' => 'Xóm 3, xã Nghi Phú'],
            ['name' => 'Bến xe Bắc Vinh', 'city' => 'Vinh', 'province' => 'Nghệ An', 'address' => '77 Lê Lợi'],
            ['name' => 'Đại học Vinh', 'city' => 'Vinh', 'province' => 'Nghệ An', 'address' => '182 Lê Duẩn'],
        ]);
    }
}
