<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MerchantPayment extends Model
{
    protected $table = 'merchant_payments_rev';

    protected $fillable = [
        'amount',
        'charges',
        'commission',
        'exchange_amount',
        'exchange_rate',
        'base_currency',
        'exchange_currency',
        'account',
        'account_name',
        'institution',
        'service',
        'product',
        'response',
    ];
}
