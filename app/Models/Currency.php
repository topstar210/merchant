<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $table = 'currencies';

    protected $fillable = [
        'name',
        'symbol',
        'code',
        'hundreds_name',
        'rate',
        'logo',
        'status',
        'default',
        'exchange_from'
    ];


    public function deposit_route()
    {
        return $this->hasMany(CurrencyPaymentMethod::class, 'currency_id')->whereHas('fee_limit_deposit')->whereHas('payment_method')->where('activated_for', 'like', '%deposit%')->with(['payment_method', 'fee_limit_deposit']);
    }

    public function transfer_route()
    {
        return $this->hasMany(CurrencyPaymentMethod::class, 'currency_id')->whereHas('fee_limit_transfer')->where('activated_for', 'like', '%withdrawal%')->with(['payment_method', 'fee_limit_transfer'])->latest()->limit(1);
    }

    public function scopeSupported($query)
    {
        $query->whereIn('code', config('env.supported_currencies'))->where('status', 'Active');
    }
}
