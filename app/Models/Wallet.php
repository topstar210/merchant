<?php

namespace App\Models;

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
//
//    public function currency_exchanges()
//    {
//        return $this->hasMany(CurrencyExchange::class);
//    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeNoLock($query)
    {
        return $query->where('lock', 0);
    }
}
