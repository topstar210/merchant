<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'first_name',
        'last_name',
        'merchant_id',
        'phone',
        'google2fa_secret',
        'formattedPhone',
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

    protected $appends = [
        'full_name'
    ];
    protected $table = 'users';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'pin',
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

    public static function boot()
    {
        parent::boot();
        self::deleting(function ($user) { // before delete() method call this
            $user->wallets()->delete();
            $user->userDetail()->delete();
        });
    }

    public function getFullNameAttribute()
    {
        return Str::title($this->first_name . " " . $this->last_name);
    }

    public function isMerchant()
    {
        return $this->type === 'merchant';
    }

    public function isAgent()
    {
        return $this->type === 'agent';
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where('merchant_id', user()->merchant_id)->where('id', $value)->firstOrFail();
    }

    public function userDetail()
    {
        return $this->hasOne(UserDetail::class);
    }

    public function wallets()
    {
        return $this->hasMany(Wallet::class)->with('currency')->latest();
    }

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'defaultCountry', 'short_name');
    }

    public function activities()
    {
        return $this->hasMany(ActivityLog::class)->latest()->limit(20);
    }

    public function scopeAgents($query)
    {
        $query->where('merchant_id', user()->merchant_id)->where('type', 'agent');
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            if (!empty($search)) {
                $query->where(function ($query) use ($search) {
                    $query->where('first_name', 'like', '%' . $search . '%')
                        ->orWhere('last_name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                });
            }
        })->when($filters['status'] ?? null, function ($query, $status) {
            if ($status === 'active') {
                $query->where('status', 'Active');
            } elseif ($status === 'inactive') {
                $query->where('status', 'Inactive');
            } elseif ($status === 'invited') {
                $query->where('reg_com', false);
            } else {
                $query->whereIn('status', ['Inactive', 'Active']);
            }
        });
    }
}
