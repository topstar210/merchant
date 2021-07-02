<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrencyPaymentMethod extends Model
{
    protected $table = 'currency_payment_methods';

    protected $fillable = [
        'currency_id',
        'method_id',
        'activated_for',
        'method_data',
        'processing_time',
    ];

    protected $hidden = [
        'method_data',
        'created_at',
        'updated_at'
    ];


    public function fee_limit_deposit()
    {
        return $this->hasOne(FeesLimit::class, 'payment_method_id', 'method_id')->where('transaction_type_id', DEPOSITS)->where('has_transaction', 'Yes');
    }

    public function fee_limit_transfer()
    {
        return $this->hasOne(FeesLimit::class, 'payment_method_id', 'method_id')->where('transaction_type_id', WITHDRAWALS)->where('has_transaction', 'Yes');
    }

    public function payment_method()
    {
        return $this->belongsTo(PaymentMethod::class, 'method_id')->where('status', 'Active');
    }
}
