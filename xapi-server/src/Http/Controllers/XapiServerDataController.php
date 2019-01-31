<?php

namespace Trax\XapiServer\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Trax\XapiServer\XapiServerServices;
use TraxAccount;

class XapiServerDataController extends Controller
{
    /**
     * Services.
     */
    protected $services;


    /**
     * Create a new controller instance.
     */
    public function __construct(XapiServerServices $services)
    {
        $this->services = $services;
    }

    /**
     * Clear all data.
     */
    public function clearAll(Request $request)
    {
        TraxAccount::authorizer()->allows('xapi_server_delete_xapi_data');
        $this->services->statements()->clear();
        $this->services->states()->clear();
        $this->services->activityProfiles()->clear();
        $this->services->agentProfiles()->clear();
        $this->services->activities()->clear();
        $this->services->agents()->clear();
        return response('', 204);
    }
    
}
