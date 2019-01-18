<?php

namespace Trax\XapiClient;

class XapiAboutApi extends XapiRemoteApi
{
    /**
     * API name.
     */
    protected $api = 'about';

    /**
     * Authentication not needed for this API.
     */
    protected $authentication = false;
    
    /**
     * xAPI version not needed for this API.
     */
    protected $version = false;
    
    /**
     * xAPI preferred lang not needed for this API.
     */
    protected $lang = false;
    
    
    /**
     * Get request.
     */
    public function get()
    {
        return $this->httpGet();
    }
    

}
