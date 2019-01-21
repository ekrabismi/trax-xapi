<?php

namespace Trax\XapiServer; 

use Trax\UI\UIRegistrar;

class XapiServerUI extends UIRegistrar
{
    /**
     * List of language files to load for JS access.
     */
    protected $langFiles = ['trax-xapi-server::common'];

    /**
     * Main menu.
     */
    protected $sideMenu = [
        'data' => [
        ],
        'settings' => [
            'xapi-data' => [
                'title' => 'trax-xapi-server::common.xapi_data',
                'route' => 'trax.ui.xapi-server.settings',
                'permission' => 'xapi_server_data_settings',
            ],
        ],
    ];

    /**
     * Init hook.
     */
    public function init()
    {
        // Statements
        if (config('trax-xapi-server.services.statements')) {

            // Side menu
            $this->sideMenu['data']['statements'] = [
                'title' => 'trax-xapi-server::common.statements',
                'route' => 'trax.ui.xapi-server.statements',
                'permission' => 'xapi_server_read_xapi_data',
            ];
        }

        // Activities
        if (config('trax-xapi-server.services.activities')) {

            // Side menu
            $this->sideMenu['data']['activities'] = [
                'title' => 'trax-xapi-server::common.activities',
                'route' => 'trax.ui.xapi-server.activities',
                'permission' => 'xapi_server_read_xapi_data',
            ];
        }

        // Agents
        if (config('trax-xapi-server.services.agents')) {

            // Side menu
            $this->sideMenu['data']['agents'] = [
                'title' => 'trax-xapi-server::common.agents',
                'route' => 'trax.ui.xapi-server.agents',
                'permission' => 'xapi_server_read_xapi_data',
            ];
        }
    }


}
