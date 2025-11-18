<?php

namespace Database\Seeders;

use App\Models\Operator;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OperatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Operator::insert([
            ['name' => 'Xe khách Hoàng Long', 'description' => 'Hãng xe nổi tiếng Bắc Nam', 'rating' => 4.5, 'contact_info' => '0123456789'],
            ['name' => 'Xe khách Phương Trang', 'description' => 'Chuyên tuyến Sài Gòn - Miền Tây', 'rating' => 4.2, 'contact_info' => '0987654321'],
            ['name' => 'Xe khách Mai Linh', 'description' => 'Chất lượng cao, phục vụ 24/7', 'rating' => 4.0, 'contact_info' => '0933444555'],
            ['name' => 'Xe khách Hải Âu', 'description' => 'Chuyên tuyến Hà Nội - Hải Phòng', 'rating' => 4.3, 'contact_info' => '0911222333'],
            ['name' => 'Xe khách Camel Travel', 'description' => 'Xe giường nằm Hà Nội - Huế - Đà Nẵng', 'rating' => 4.1, 'contact_info' => '0901452387'],
            ['name' => 'Xe khách Sao Nghe Limousine', 'description' => 'Xe chuyên tuyến Vinh - Hà Nội', 'rating' => 4.2, 'contact_info' => '0982345678'],
            ['name' => 'Xe khách Hieu Vien', 'description' => 'Xe giường nằm tuyến Vinh - Hà Nội', 'rating' => 4.3, 'contact_info' => '0935678901'],
            ['name' => 'Xe khách Nam Quynh Anh', 'description' => 'Xe giường VIP tuyến Vinh - Hà Nội', 'rating' => 4.1, 'contact_info' => '0914785623'],
        ]);
    }
}
