<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\BookingPassenger;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CancelExpiredBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cancel-expired-bookings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hủy các giao dịch quá hạn 15 phút';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('Lệnh hủy booking đang chạy lúc: ' . now());

        $expiredBookings = Booking::with('schedule')
            ->whereIn('status', ['pending', 'waiting_payment'])
            ->where('expires_at', '<', now())
            ->get();

        if ($expiredBookings->isEmpty()) {
            $this->info('Không có đơn hàng nào hết hạn.');
            return;
        }

        foreach ($expiredBookings as $booking) {
            DB::transaction(function () use ($booking) {
                $booking->update(['status' => 'cancelled']);
                $booking->passengers()->update(['status' => 'cancelled']);
                if ($booking->schedule) {
                    $booking->schedule->increment('seats_available', $booking->num_passengers);
                }
            });
            $this->info("Đã hủy đơn hàng: {$booking->code}");
        }
    }
}
