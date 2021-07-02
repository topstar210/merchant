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

    public function route()
    {
        return $this->hasOne(CurrencyPaymentMethod::class, 'id', 'payment_method_id')->with(['payment_method']);
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class, 'wallet_id')->with('currency')->latest();
    }
}
