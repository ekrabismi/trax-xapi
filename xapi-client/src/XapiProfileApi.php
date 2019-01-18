<?php

namespace Trax\XapiClient;

abstract class XapiProfileApi extends XapiRemoteApi
{
    /**
     * Profile field (agent or activityId).
     * Must be overriden.
     */
    protected $field;
    
    
    /**
     * GET request.
     */
    public function get($itemId, $profileId = null, $since = null)
    {
        if (!is_string($itemId)) $itemId = json_encode($itemId);
        $args = [$this->field => $itemId];
        if (isset($profileId)) $args['profileId'] = $profileId;
        if (isset($since)) $args['since'] = $since;
        return $this->httpGet($args);
    }
    
    /**
     * POST request with JSON.
     */
    public function postJson($content, $itemId, $profileId)
    {
        if (!is_string($itemId)) $itemId = json_encode($itemId);
        $this->httpPost($content);
        $this->query[$this->field] = $itemId;
        $this->query['profileId'] = $profileId;
        return $this;
    }
    
    /**
     * POST request.
     */
    public function post($content, $type, $itemId, $profileId)
    {
        $this->postJson($content, $itemId, $profileId);
        $this->headers['Content-Type'] = $type;
        return $this;
    }
    
    /**
     * PUT request with JSON.
     */
    public function putJson($content, $itemId, $profileId, $etag = null)
    {
        if (!is_string($itemId)) $itemId = json_encode($itemId);
        $this->httpPut($content);
        $this->query[$this->field] = $itemId;
        $this->query['profileId'] = $profileId;
        if (isset($etag)) $this->headers['If-Match'] = $etag;
        else $this->headers['If-None-Match'] = '*';
        return $this;
    }
    
    /**
     * PUT request.
     */
    public function put($content, $type, $itemId, $profileId, $etag = null)
    {
        $this->putJson($content, $itemId, $profileId, $etag);
        $this->headers['Content-Type'] = $type;
        return $this;
    }
    
    /**
     * DELETE request.
     */
    public function delete($itemId, $profileId)
    {
        if (!is_string($itemId)) $itemId = json_encode($itemId);
        $args = [
            $this->field =>  $itemId,
            'profileId' =>  $profileId,
        ];
        return $this->httpDelete($args);
    }
    

}
