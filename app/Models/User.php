<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Traits\HasPermissions;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes, HasRoles, HasPermissions, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'role',
        'name',
        'email',
        'phone',
        'password',
        'profile_picture',
        'otp',
        'otp_expires_at',
        'email_verified_at',
        'is_active',
        'promocode',
        'is_available',
        'gender',
        'address',
        'city',
        'country',
        'zipcode',
        'dob',
        'country_code',
        'customer_stripe_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

     protected static function boot()
    {
        parent::boot();
        static::deleting(function ($user) {
            $user->bookings()->delete();
            $user->bookingCancellations()->delete();
            $user->vehicles()->delete();
            $user->booking()->delete();
            $user->ratings()->delete();
            $user->earnings()->delete();
            $user->devices()->delete();
        });
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'cleaner_id');
    }

    public function bookingCancellations()
    {
        return $this->hasMany(BookingCancellation::class, 'cleaner_id');
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class, 'customer_id');
    }

    public function booking()
    {
        return $this->hasMany(Booking::class, 'customer_id');
    }

    public function completed_job(){
        return $this->hasMany(Booking::class, 'cleaner_id')->where('status','completed');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'cleaner_id');
    }

    public function earnings()
    {
        return $this->hasMany(CleanerEarning::class, 'cleaner_id');
    }

    public function totalEarned(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->earnings()->sum('amount')
        );
    }

    public function totalTipEarned(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->earnings()->sum('tip')
        );
    }

    public function completedBookings()
    {
        return $this->hasMany(Booking::class, 'cleaner_id')
                    ->where('status', 'completed');
    }

    public function monthlyAverageCompletedBookings(): Attribute
    {
        return Attribute::make(
            get: function () {
                $totalCompleted = $this->completedBookings()->count();

                if ($totalCompleted === 0) {
                    return 0;
                }

                $first = $this->completedBookings()->orderBy('created_at')->first();
                $last  = $this->completedBookings()->orderByDesc('created_at')->first();

                if (! $first || ! $last) {
                    return 0;
                }

                $months = $first->created_at->diffInMonths($last->created_at) + 1;

                return round($totalCompleted / $months, 2);
            }
        );
    }

    public function devices()
    {
        return $this->hasOne(UserDevice::class, 'user_id', 'id');
    }


}
