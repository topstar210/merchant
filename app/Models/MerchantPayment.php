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
        'initiator_id',
        'payment_method_id',
    ];

    protected $appends = [
        'total_amount'
    ];

    public function getAttribute($key)
    {
        [$key, $path] = preg_split('/(->|\.)/', $key, 2) + [null, null];

        return data_get(parent::getAttribute($key), $path);
    }

    public function getTotalAmountAttribute()
    {
        return (double)($this->amount + $this->charges);
    }

    public function getRouteKeyName()
    {
        return 'reference';
    }

    protected $casts = [
        'response' => 'array',
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
        })->when($filters['status'] ?? null, function ($query, $status) {
            if (!empty($status)) {
                $query->where('status', $status);
            }
        })->when($filters['wallet'] ?? null, function ($query, $wallet) {
            if (!empty($wallet)) {
                $query->where('wallet_id', $wallet);
            }
        })->when($filters['initiator'] ?? null, function ($query, $initiator) {
            if (!empty($initiator)) {
                $query->where('user_id', $initiator);
            }
        })->when($filters['service'] ?? null, function ($query, $service) {
            if (!empty($service)) {
                $query->where('product', $service);
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

    public function recipient_wallet()
    {
        return $this->belongsTo(Wallet::class, 'response->recipient_wallet_id')->with(['currency', 'user'])->latest();
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function transaction_types()
    {
        return $this->belongsTo(TransactionType::class, 'transaction_type');
    }

    public function debit_lock()
    {
        return $this->hasOne(WalletDebitLock::class, 'reference', 'reference');
    }

    public function scopeSummary($query, array $filters)
    {
        $query->where('status', 'Success')
            ->when($filters['type'] ?? null, function ($query, $type) {
                if (!is_null($type)) {
                    $query->where('transaction_type', $type);
                }
            })->when($filters['date'] ?? null, function ($query, $date) {
                if (!is_null($date)) {
                    $query->whereDate('created_at', $date);
                }
            })->when($filters['wallet'] ?? null, function ($query, $wallet) {
                if (!is_null($wallet)) {
                    $query->where('wallet_id', $wallet);
                }
            });
    }
}
