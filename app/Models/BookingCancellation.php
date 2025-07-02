<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingCancellation extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'cleaner_id',
    ];

    // Optionally, you can define relationships if needed
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
    public function cleaner()
    {
        return $this->belongsTo(User::class, 'cleaner_id');
    }
}
