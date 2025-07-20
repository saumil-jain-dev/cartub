<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    //
    use SoftDeletes, HasFactory;

     protected $fillable = [
        'booking_id',
        'amount',
        'payment_method',
        'status',
        'transaction_id',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'payment_type' => 'string'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'id', 'booking_id');
    }

    public function bookings()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'id');
    }

    public function getPaymentTypeAttribute(){
        $paymentMethods = [
            'google_pay' => 'Google Pay',
            'card' => 'Card',
            'apple_pay' => 'Apple Pay',
            'paypal' => 'PayPal',
            // Add other mappings here
        ];
    
        return $paymentMethods[$this->attributes['payment_method']] ?? '-';
    }
}
