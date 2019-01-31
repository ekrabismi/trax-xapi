<?php

namespace Trax\XapiClient;

class XapiAgentApi extends XapiRemoteApi
{
    /**
     * API name.
     */
    protected $api = 'agents';


    /**
     * GET request.
     */
    public function get($agent)
    {
        $args = ['agent' => $agent];
        return $this->httpGet($args);
    }
    

}
