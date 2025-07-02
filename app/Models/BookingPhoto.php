<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookingPhoto extends Model
{
    //
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'booking_id',
        'photo_type',
        'photo_path',
        'photo_taken_at',
    ];

    protected $casts = [
        'photo_taken_at' => 'datetime',
    ];
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
