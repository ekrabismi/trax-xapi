<?php

namespace Trax\XapiServer; 

use Trax\Account\PermissionsRegistrar;

class XapiServerPermissions extends PermissionsRegistrar
{

    /**
     * Get permissions.
     */
    protected function permissions()
    {
        return [

            // Read
            'xapi_server_read_xapi_data' => [
                'name' => __('trax-xapi-server::common.perm_read_xapi_data'),
                'class' => Permissions\XapiDataReadPermission::class,
            ],

            // Settings
            'xapi_server_data_settings' => [
                'name' => __('trax-xapi-server::common.perm_xapi_data_settings'),
                'class' => Permissions\XapiDataSettingsPermission::class,
            ],

            // Delete
            'xapi_server_delete_xapi_data' => [
                'name' => __('trax-xapi-server::common.perm_delete_xapi_data'),
                'class' => Permissions\XapiDataDeletePermission::class,
            ],


            // Store default permissions

            'xapi_server_statement_default' => [
                'class' => Permissions\StatementDefaultPermission::class,
                'model' => 'Trax\XapiServer\Models\Statement',
            ],
            'xapi_server_agent_default' => [
                'class' => Permissions\AgentDefaultPermission::class,
                'model' => 'Trax\XapiServer\Models\Agent',
            ],
            'xapi_server_activity_default' => [
                'class' => Permissions\ActivityDefaultPermission::class,
                'model' => 'Trax\XapiServer\Models\Activity',
            ],

        ];
    }

}

