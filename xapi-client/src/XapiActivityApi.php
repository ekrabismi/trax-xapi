<?php

namespace Trax\XapiClient;

class XapiActivityApi extends XapiRemoteApi
{
    /**
     * API name.
     */
    protected $api = 'activities';

    /**
     * GET request.
     */
    public function get($activityId)
    {
        $args = ['activityId' => $activityId];
        return $this->httpGet($args);
    }
    

}
