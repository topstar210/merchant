<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends \Spatie\Permission\Models\Role
{
    protected $table = "roles_rev";

    /**
     * List of system roles
     *
     * @return string[]
     */
    public static function roleList()
    {
        return [
            'merchant',
            'agent'
        ];
    }
}
