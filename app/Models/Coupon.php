<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    //
      use SoftDeletes, HasFactory;
    protected $fillable = [
        'code',
        'type',
        'discount_type',
        'discount_value',
        'min_amount',
        'usage_limit',
        'used_count',
        'valid_from',
        'valid_until',
        'is_active',
        'user_ids',
        'zipcodes',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'min_amount' => 'decimal:2',
        'used_count' => 'integer',
        'usage_limit' => 'integer',
        
    ];
}
