<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Booking extends Model
{
    //
     use SoftDeletes, HasFactory;

     protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            $booking->booking_number = self::generateUniqueOrderNumber();
        });
    }
     public static function generateUniqueOrderNumber()
    {
        do {
            $bookingNumber = (string) random_int(100000000000000, 999999999999999); // 15-digit
        } while (self::where('booking_number', $bookingNumber)->exists());

        return $bookingNumber;
    }
     protected $fillable = [
        'booking_number',
        'customer_id',
        'cleaner_id',
        'vehicle_id',
        'service_id',
        'wash_type_id',
        'payment_status',
        'status',
        'scheduled_date',
        'scheduled_time',
        'job_start_time',
        'job_end_time',
        'job_duration', // duration in minutes
        'address',
        'latitude',
        'longitude',
        'coupon_id',
        'gross_amount',
        'discount_amount',
        'total_amount',
        'notes',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'total_amount' => 'decimal:2',
        // 'scheduled_date' => 'date',
        // 'scheduled_time' => 'datetime:H:i:s',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }

    public function cleaner()
    {
        return $this->belongsTo(User::class, 'cleaner_id', 'id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id', 'id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id', 'id');
    }

    public function washType()
    {
        return $this->belongsTo(WashType::class, 'wash_type_id', 'id');
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_id', 'id');
    }
     public function payment()
    {
        return $this->hasOne(Payment::class, 'booking_id', 'id');
    }

    public function beforePhoto()
    {
        return $this->hasMany(BookingPhoto::class,'booking_id','id')->where('photo_type', 'before');
    }

    public function afterPhoto()
    {
        return $this->hasMany(BookingPhoto::class,'booking_id','id')->where('photo_type', 'after');
    }

    public function rating()
    {
        return $this->hasOne(Rating::class, 'booking_id', 'id');
    }

    public function tip()
    {
        return $this->hasOne(CleanerEarning::class, 'booking_id', 'id');
    }

    public function cleaner_location(){
        return $this->hasOne(CleanerLocation::class, 'cleaner_id', 'cleaner_id');
    }
}
