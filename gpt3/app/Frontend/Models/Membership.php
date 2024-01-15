<?php

namespace App\Frontend\Models;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    protected $table = 'memberships';

    protected $fillable = [
        'name',
        'key',
    ];

    protected $casts = [
        'name' => 'string',
        'key' => 'string',
    ];
}
