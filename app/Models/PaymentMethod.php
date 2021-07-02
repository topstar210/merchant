<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $table    = 'payment_methods';
    protected $fillable = [
        'name',
        'status'
    ];
    public $timestamps  = false;

    public function getRouteKeyName()
    {
        return 'name';
    }
}
