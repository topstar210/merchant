<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    protected $table   = 'user_details';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'country_id',
        'email_verification',
        'phone_verification_code',
        'two_step_verification_type',
        'two_step_verification_code',
        'two_step_verification',
        'city',
        'state',
        'address_1',
        'address_2',
        'default_currency',
        'timezone',
        'last_login_at',
        'last_login_ip',
        'dob',
        'gender'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
}
