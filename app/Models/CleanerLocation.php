<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CleanerLocation extends Model
{
    //
     protected $fillable = [
        'cleaner_id',
        'latitude',
        'longitude',
        'is_available',
        'location_updated_at',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'is_available' => 'boolean',
        'location_updated_at' => 'datetime',
    ];

    public function cleaner()
    {
        return $this->belongsTo(User::class, 'cleaner_id', 'id');
    }
}
