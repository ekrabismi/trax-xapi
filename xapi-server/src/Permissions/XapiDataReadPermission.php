<?php

namespace Trax\XapiServer\Permissions;

use Trax\Account\Permissions\PermissionModel;

class XapiDataReadPermission extends PermissionModel
{

    /**
     * Get permissions.
     */
    public function subpermissions()
    {
        return [
            'Trax\XapiServer\Models\Activity' => ['read'],
            'Trax\XapiServer\Models\Agent' => ['read'],
            'Trax\XapiServer\Models\Statement' => ['read'],
        ];
    }


}

