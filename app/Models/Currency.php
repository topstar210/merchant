<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $table    = 'currencies';

    protected $fillable = [
        'name',
        'symbol',
        'code',
        'hundreds_name',
        'rate',
        'logo',
        'status',
        'default',
        'exchange_from'
    ];

    protected $appends  = ['org_symbol'];

    public $timestamps  = false;
}
