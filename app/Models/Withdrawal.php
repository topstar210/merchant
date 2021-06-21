<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    protected $table = 'withdrawals';

    public $timestamps = true;

    protected $fillable = [
        'user_id', 'currency_id', 'payment_method_id', 'uuid', 'charge_percentage', 'charge_fixed', 'subtotal', 'amount', 'payment_method_info', 'status'];
}
