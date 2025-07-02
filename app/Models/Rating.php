<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rating extends Model
{
    //
     use SoftDeletes, HasFactory;

     protected $fillable = [
        'booking_id',
        'customer_id',
        'cleaner_id',
        'rating',
        'comment',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }

    public function cleaner()
    {
        return $this->belongsTo(User::class, 'cleaner_id', 'id');
    }
}
