<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    protected $table = 'merchants_rev';

    protected $fillable = [
        'merchant_group_id',
        'mid',
        'merchant_name',
        'merchant_email',
        'merchant_address',
        'merchant_phone',
        'country',
        'currency',
        'business_certificate',
        'logo',
        'site_url',
        'commission',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(MerchantPayment::class)->latest();
    }

    public function lien()
    {
        return $this->hasOne(MerchantLien::class);
    }

}
