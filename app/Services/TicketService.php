<?php

namespace App\Services;

use App\Models\Ticket;
use Illuminate\Support\Str;

class TicketService
{
    public function generateTickets($booking)
    {
        foreach ($booking->passengers as $p) {
            Ticket::create([
                'booking_id' => $booking->id,
                'ticket_code' => 'TKT-' . strtoupper(Str::random(8)),
                'issued_at' => now(),
                'valid_from' => $booking->schedule->departure_datetime,
                'valid_to' => $booking->schedule->arrival_datetime,
                'seat_number'  => $p->seat_number,
                'status' => 'unused',
                'qr_code_data' => 'BOOKING:' . $booking->code . ';SEAT:' . $p->seat_number,
            ]);
        }
    }
}
