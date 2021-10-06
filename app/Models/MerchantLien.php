<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MerchantLien extends Model
{
    use HasFactory;

    protected $table = 'merchant_liens';

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id', 'id');
    }

    public function getRouteKeyName()
    {
        return 'id';
    }
}
