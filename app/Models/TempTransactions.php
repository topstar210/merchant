<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempTransactions extends Model
{
    use HasFactory;

    protected $table = 'temp_transactions';

    protected $guarded = [];

    public function getRouteKeyName()
    {
        return 'reference';
    }

    protected $casts = [
        'data' => 'array',
    ];

    public function route()
    {
        return $this->hasOne(CurrencyPaymentMethod::class, 'id', 'payment_method_id')->with(['payment_method']);
    }

    public function getAttribute($key)
    {
        [$key, $path] = preg_split('/(->|\.)/', $key, 2) + [null, null];

        return data_get(parent::getAttribute($key), $path);
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class, 'wallet_id')->with('currency')->latest();
    }

    public function recipient_wallet()
    {
        return $this->belongsTo(Wallet::class, 'data->recipient_wallet_id')->with('currency')->latest();
    }
}
