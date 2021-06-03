<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogError extends Model
{

    protected $guarded = [];

    protected $casts = [
        'trace' => 'array',
        'params' => 'array',
        'header' => 'array'
    ];

}
