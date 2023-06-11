<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coin extends Model
{
    protected $table = 'coins';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'symbol',
        'name',
        'platforms',
    ];

    protected $casts = [
        'platforms' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        ];
}
