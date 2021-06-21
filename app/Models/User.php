<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'first_name',
        'last_name',
        'phone',
        'google2fa_secret',
        'defaultCountry',
        'carrierCode',
        'email',
        'password',
        'pin',
        'phrase',
        'status',
        'reg_com',
        "suspend_account_date",
        "suspend_payout_date",
        'picture',
        'account_number',
        'address_verified',
        'identity_verified',
    ];
    protected $table = 'users';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'phrase',
        'google2fa_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function userDetail()
    {
        return $this->hasOne(UserDetail::class);
    }

    public function wallets()
    {
        return $this->hasMany(Wallet::class)->with('currency');
    }

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

}
