<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeesLimit extends Model
{
    use Compoships;

    protected $table = 'fees_limits';
    protected $fillable = [
        'currency_id',
        'transaction_type_id',
        'payment_method_id',
        'charge_percentage',
        'charge_fixed',
        'min_limit',
        'max_limit',
        'processing_time',
        'has_transaction',
    ];

}
