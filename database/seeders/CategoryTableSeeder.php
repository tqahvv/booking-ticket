<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Cẩm nang Đặt vé',
            'Kinh nghiệm Du lịch Đà Lạt',
            'Khuyến mãi & Ưu đãi',
            'Review Nhà xe',
            'Địa điểm Check-in',
        ];

        foreach ($categories as $name) {
            DB::table('categories')->insert([
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => 'Các bài viết về ' . strtolower($name),
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
