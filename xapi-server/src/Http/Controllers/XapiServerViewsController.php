<?php

namespace Trax\XapiServer\Http\Controllers;

use Illuminate\Http\Request;

use Trax\UI\Http\Controllers\ViewsController;
use Trax\Account\DataStoreAuthorizer;

class XapiServerViewsController extends ViewsController
{
    use DataStoreAuthorizer;
    

    /**
     * Statements.
     */
    public function statements(Request $request)
    {
        $this->authorizerModel('Trax\XapiServer\Models\Statement')->allowsRead($request);
        $this->nav->title = __('trax-xapi-server::common.statements');
        return $this->view('trax-xapi-server::statements');
    }
    
    /**
     * Activities.
     */
    public function activities(Request $request)
    {
        $this->authorizerModel('Trax\XapiServer\Models\Activity')->allowsRead($request);
        $this->nav->title = __('trax-xapi-server::common.activities');
        return $this->view('trax-xapi-server::activities');
    }
    
    /**
     * Agents.
     */
    public function agents(Request $request)
    {
        $this->authorizerModel('Trax\XapiServer\Models\Agent')->allowsRead($request);
        $this->nav->title = __('trax-xapi-server::common.agents');
        return $this->view('trax-xapi-server::agents');
    }
    
    /**
     * Settings.
     */
    public function settings(Request $request)
    {
        $this->account->authorizer()->allows('xapi_server_data_settings');
        $this->nav->title = __('trax-xapi-server::common.xapi_data_settings');
        return $this->view('trax-xapi-server::settings');
    }
    

}
