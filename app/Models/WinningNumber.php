<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WinningNumber extends Model
{
    protected $fillable = [
        'number',
        'draw_date',
    ];
}
