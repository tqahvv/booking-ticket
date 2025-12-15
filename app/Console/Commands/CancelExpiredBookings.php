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
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredBookings = Booking::whereIn('status', ['pending', 'waiting_payment'])
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->get();

        foreach ($expiredBookings as $booking) {

            DB::transaction(function () use ($booking) {

                $booking->status = 'cancelled';
                $booking->save();

                BookingPassenger::where('booking_id', $booking->id)
                    ->update(['status' => 'cancelled']);

                Log::info("Booking {$booking->id} cancelled due to timeout");
            });
        }
    }
}
