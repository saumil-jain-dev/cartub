<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    //
     /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'customer_id',
        'make',
        'model',
        'year',
        'color',
        'license_plate',
        'fetched_via_dvla'
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }
}
