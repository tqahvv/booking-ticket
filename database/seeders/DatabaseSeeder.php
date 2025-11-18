<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Post;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // LocationSeeder::class,
            // OperatorSeeder::class,
            // VehicleTypeSeeder::class,
            // AmenitySeeder::class,
            // RouteSeeder::class,
            // StopSeeder::class,
            // ScheduleSeeder::class,
            // PaymentMethodSeeder::class,
            // RoleSeeder::class,
            // PermissionSeeder::class,
            // RolePermissionSeeder::class,
            // UserSeeder::class,
            // ImageSeeder::class,
            CategoryTableSeeder::class,
            PostTableSeeder::class,
            PostCategoryTableSeeder::class,
        ]);
    }
}
