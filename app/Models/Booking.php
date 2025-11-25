<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'user_id',
        'schedule_id',
        'payment_method_id',
        'promotion_id',
        'booking_date',
        'total_price',
        'num_passengers',
        'status',
        'currency',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function passengers()
    {
        return $this->hasMany(BookingPassenger::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function getStatusLabelAttribute()
    {
        return [
            'pending' => 'Chờ xử lý',
            'confirmed' => 'Đã xác nhận',
            'cancelled' => 'Đã hủy',
            'expired' => 'Hết hạn',
            'waiting_transfer' => 'Chờ chuyển khoản',
        ][$this->status] ?? 'Không rõ';
    }

    public function getStatusColorAttribute()
    {
        return [
            'pending' => 'secondary',
            'confirmed' => 'success',
            'cancelled' => 'danger',
            'expired' => 'dark',
            'waiting_transfer' => 'warning',
        ][$this->status] ?? 'secondary';
    }
}
