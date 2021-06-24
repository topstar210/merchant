<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends \Spatie\Permission\Models\Permission
{

    protected $table = "permissions_rev";

    /**
     * List of system default Permissions
     *
     * @return string[]
     */
    public static function systemDefaultPermissions()
    {
        return [
            'dashboard',
            'agents',
            'add_agent',
            'delete_agent',
        ];
    }


    public static function agentDefaultPermissions()
    {
        return [
            'dashboard'
        ];
    }
}
