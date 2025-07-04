<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDevice extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'user_id',
        'device_id',
        'device_type',
        'device_token',
        'os_version',
        'app_version',
        'device_name',
        'model_name',
        'status'
    ];
}
