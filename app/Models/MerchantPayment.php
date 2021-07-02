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
        'reference',
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
        'balance_before',
        'balance_after',
        'product',
        'response',
        'status',
        'transaction_id',
        'wallet_id',
        'payment_method_id',
    ];

    protected $appends = [
        'total_amount'
    ];

    public function getTotalAmountAttribute()
    {
        return (double)($this->amount + $this->charges);
    }

    public function getRouteKeyName()
    {
        return 'reference';
    }

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
        })->when($filters['status'] ?? null, function ($query, $status) {
            if (!empty($status)) {
                $query->where('status', $status);
            }
        });
    }

    public function payment_method()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function transaction_type()
    {
        return $this->belongsTo(TransactionType::class, 'transaction_type');
    }
}
