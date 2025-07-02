<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CleanerEarning extends Model
{
    //
     use SoftDeletes, HasFactory;

     protected $fillable = [
        'cleaner_id',
        'booking_id',
        'amount',
        'tip',
        'bonus',
        'earned_on',
        'tip_earned_on',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'tip' => 'decimal:2',
        'bonus' => 'decimal:2',
        'earned_on' => 'datetime',
        'tip_earned_on' => 'datetime',
    ];

    public function cleaner()
    {
        return $this->belongsTo(User::class, 'cleaner_id', 'id');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'id');
    }
}
