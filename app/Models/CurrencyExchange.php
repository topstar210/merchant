<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrencyExchange extends Model
{
    protected $table    = 'currency_exchanges';
    public $timestamps  = true;
    protected $fillable = [
        'user_id',
        'from_wallet',
        'to_wallet',
        'currency_id',
        'uuid',
        'exchange_rate',
        'amount',
        'type',
        'status',
    ];

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function fromWallet()
    {
        return $this->belongsTo(Wallet::class, 'from_wallet');
    }

    public function toWallet()
    {
        return $this->belongsTo(Wallet::class, 'to_wallet');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class, 'transaction_reference_id', 'id');
    }
}
