<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaymentMethod::insert([
            ['name' => 'Tiền mặt', 'description' => 'Thanh toán trực tiếp khi lên xe', 'details' => null, 'active_flag' => true],
            ['name' => 'Chuyển khoản ngân hàng', 'description' => 'Thanh toán qua tài khoản ngân hàng', 'details' => null, 'active_flag' => true],
            ['name' => 'Ví điện tử Momo', 'description' => 'Thanh toán qua Momo', 'details' => null, 'active_flag' => true],
            ['name' => 'ZaloPay', 'description' => 'Thanh toán qua ZaloPay', 'details' => null, 'active_flag' => true],
            ['name' => 'Thẻ tín dụng', 'description' => 'Thanh toán qua Visa/MasterCard', 'details' => null, 'active_flag' => true],
        ]);
    }
}
