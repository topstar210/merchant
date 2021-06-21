<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    protected $table = 'deposits';

    public $timestamps = false;

    protected $fillable = [
        'user_id', 'currency_id', 'payment_method_id', 'bank_id', 'file_id', 'uuid', 'charge_percentage', 'charge_fixed', 'amount', 'status'
    ];

}
