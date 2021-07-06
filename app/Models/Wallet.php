<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $table = 'wallets';
    protected $fillable = [
        'user_id',
        'currency_id',
        'balance',
        'limit_amount',
        'is_default'
    ];

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function transactions()
    {
        return $this->hasMany(MerchantPayment::class);
    }

    public function today_out_sum()
    {
        return $this->hasMany(MerchantPayment::class)->whereDate('created_at', Carbon::now())->whereIn('transaction_type', [WITHDRAWALS])->where('status', 'Success')->latest();
    }

    public function debit_locks()
    {
        return $this->hasMany(WalletDebitLock::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
