<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::insert([
            [
                'name' => 'Admin System',
                'email' => 'admin@booking.com',
                'password' => bcrypt('admin123'),
                'status' => 'active',
                'phone' => '0123456789',
                'avatar' => null,
                'address' => 'Hà Nội',
                'role_id' => 1,
            ],
            [
                'name' => 'Booking Staff',
                'email' => 'staff@booking.com',
                'password' => bcrypt('staff123'),
                'status' => 'active',
                'phone' => '0987654321',
                'avatar' => null,
                'address' => 'Hồ Chí Minh',
                'role_id' => 2,
            ],
            [
                'name' => 'Nguyen Van A',
                'email' => 'customer@booking.com',
                'password' => bcrypt('customer123'),
                'status' => 'active',
                'phone' => '0911222333',
                'avatar' => null,
                'address' => 'Nghê An',
                'role_id' => 3,
            ],
        ]);
    }
}
