<?php

namespace Trax\XapiClient;

class XapiStateApi extends XapiRemoteApi
{    
    /**
     * API name.
     */
    protected $api = 'activities/state';

    
    /**
     * GET request.
     */
    public function get($activityId, $agent, $stateId = null, $registration = null, $since = null)
    {
        $args = [
            'activityId' => $activityId,
            'agent' => json_encode($agent),
        ];
        if (isset($stateId)) $args['stateId'] = $stateId;
        if (isset($registration)) $args['registration'] = $registration;
        if (isset($since)) $args['since'] = $since;
        return $this->httpGet($args);
    }
    
    /**
     * POST request with JSON.
     */
    public function postJson($content, $activityId, $agent, $stateId, $registration = null)
    {
        $this->httpPost($content);
        $this->query['activityId'] = $activityId;
        $this->query['agent'] = json_encode($agent);
        $this->query['stateId'] = $stateId;
        if (isset($registration)) $this->query['registration'] = $registration;
        return $this;
    }
    
    /**
     * POST request.
     */
    public function post($content, $type, $activityId, $agent, $stateId, $registration = null)
    {
        $this->postJson($content, $activityId, $agent, $stateId, $registration);
        $this->headers['Content-Type'] = $type;
        return $this;
    }
    
    /**
     * PUT request with JSON.
     */
    public function putJson($content, $activityId, $agent, $stateId, $registration = null)
    {
        $this->httpPut($content);
        $this->query['activityId'] = $activityId;
        $this->query['agent'] = json_encode($agent);
        $this->query['stateId'] = $stateId;
        if (isset($registration)) $this->query['registration'] = $registration;
        return $this;
    }
    
    /**
     * PUT request.
     */
    public function put($content, $type, $activityId, $agent, $stateId, $registration = null)
    {
        $this->putJson($content, $activityId, $agent, $stateId, $registration);
        $this->headers['Content-Type'] = $type;
        return $this;
    }
    
    /**
     * DELETE request.
     */
    public function delete($activityId, $agent, $stateId = null, $registration = null)
    {
        $args = [
            'activityId' => $activityId,
            'agent' => json_encode($agent),
        ];
        if (isset($stateId)) $args['stateId'] = $stateId;
        if (isset($registration)) $args['registration'] = $registration;
        return $this->httpDelete($args);
    }
    

}
