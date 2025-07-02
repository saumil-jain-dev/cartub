<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    //
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'name',
        'description',
        'price',
        'duration_minutes',
        'is_active',
        'type',
        'image',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'decimal:2',
    ];
}
