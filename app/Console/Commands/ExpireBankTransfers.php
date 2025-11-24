<?php

namespace App\Console\Commands;

use App\Models\BankTransfer;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class ExpireBankTransfers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bank:expire-transfers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hủy các giao dịch chuyển khoản quá hạn 15 phút';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredTransfers = BankTransfer::where('status', 'pending')
            ->where('expires_at', '<', Carbon::now())
            ->get();

        foreach ($expiredTransfers as $transfer) {
            $transfer->status = 'expired';
            $transfer->save();

            $booking = $transfer->booking;
            $booking->status = 'cancelled';
            $booking->save();

            $schedule = $booking->schedule;
            $schedule->increment('seats_available', $booking->num_passengers);

            $this->info("Booking {$booking->id} expired and cancelled.");
        }
    }
}
