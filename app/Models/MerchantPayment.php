<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class MerchantPayment extends Model
{
    protected $table = 'merchant_payments_rev';

    protected $fillable = [
        'merchant_id',
        'user_id',
        'transaction_type',
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
        'status',
        'transaction_id',
        'wallet_id',
        'payment_method_id',
    ];

    public function scopeDeposits($query)
    {
        $query->where('transaction_type', DEPOSITS);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['date'] ?? null, function ($query, $date) {
            if (!empty($date)) {
                $query->whereDate('created_at', $date);
            }
        });
    }
}
