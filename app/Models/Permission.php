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
            'view dashboard',
            '',
        ];
    }


    public static function agentDefaultPermissions()
    {
        return [

        ];
    }
}
