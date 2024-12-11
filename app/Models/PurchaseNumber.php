<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PurchaseNumber extends Model
{

    protected $fillable = [
        'number',
        'purchase_date',
        'amount',
        'customer_name',
        'customer_phone',
        'customer_facebook',
        'customer_viber',
    ];
    protected $casts = [
        'purchase_date' => 'timestamp'

    ];

    public function getPurchaseDateAttribute($date)
    {
        if ($date)
            return Carbon::parse($date)->format('Y-m-d');

        return null;
    }
}
