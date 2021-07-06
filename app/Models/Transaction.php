<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';

    protected $fillable = [
        'user_id',
        'end_user_id',
        'currency_id',
        'payment_method_id',
        'merchant_id',
        'bank_id',
        'file_id',
        'uuid',
        'refund_reference',
        'transaction_reference_id',
        'transaction_type_id',
        'user_type',
        'emails',
        'phone',
        'subtotal',
        'percentage',
        'charge_percentage',
        'charge_fixed',
        'total',
        'note',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function deposit()
    {
        return $this->hasOne(Deposit::class, 'uuid');
    }

    public function withdrawal()
    {
        return $this->hasOne(Withdrawal::class, 'uuid');
    }
}
