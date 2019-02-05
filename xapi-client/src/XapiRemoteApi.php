<?php

namespace Trax\XapiClient;

use Faker\Factory;

abstract class XapiRemoteApi
{

    /**
     * API name. You may override this.
     */
    protected $api = 'statements';
    
    /**
     * Authentication needed? You may override this.
     */
    protected $authentication = true;
    
    /**
     * xAPI version control needed? You may override this.
     */
    protected $version = true;

    /**
     * Include Accept-Language in requests? You may override this.
     */
    protected $lang = true;
    
    /**
     * API endpoint.
     */
    protected $endpoint;
    
    /**
     * URL (endpoint + API).
     */
    protected $url;
    
    /**
     * API method.
     */
    protected $method = 'GET';
    
    /**
     * Request headers.
     */
    protected $headers = array();
    
    /**
     * Request querystring params.
     */
    protected $query = array();
    
    /**
     * Request content.
     */
    protected $content = '';
    
    /**
     * HTTP service.
     */
    protected $http;
    
    /**
     * Faker
     */
    protected $faker;
 
    /**
     * Is the request content a JSON type?
     */
    protected $json;
    

    
    /**
     * Construct.
     */
    public function __construct()
    {
        $this->endpoint = config('trax-xapi-client.lrs.endpoint');
        if (!$this->endpoint) $this->endpoint = config('app.url').'/trax/ws/xapi';
        $this->url = $this->endpoint.'/'.$this->api;
        $this->http = new HttpClient();
        $this->faker = Factory::create();
    }
    
    /**
     * Get request.
     */
    protected function httpGet($args = [])
    {
        $this->method = 'GET';
        $this->headers = $this->headers(['lang']);
        $this->query = $args;
        return $this;
    }
    
    /**
     * Post request.
     */
    protected function httpPost($content)
    {
        $this->method = 'POST';
        $this->headers = $this->headers();
        $this->query = [];
        $this->content = $content;
        $this->json = !is_string($content);
        if ($this->json) $this->headers['Content-Type'] = 'application/json';
        return $this;
    }
    
    /**
     * Put request.
     */
    protected function httpPut($content)
    {
        $this->httpPost($content);
        $this->method = 'PUT';
        return $this;
    }
    
    /**
     * Delete request.
     */
    protected function httpDelete($args = [])
    {
        $this->method = 'DELETE';
        $this->headers = $this->headers();
        $this->query = $args;
        return $this;
    }
    
    /**
     * Return the request in a JSON format.
     */
    public function json()
    {
        $res = (object)array(
            'endpoint' => $this->endpoint,
            'api' => $this->api,
            'url' => $this->url,
            'method' => $this->method,
            'headers' => (object)$this->headers,
            'query' => (object)$this->query,
            'content' => $this->content,
        );

        // Encode JSON content
        if (!is_string($res->content)) $res->content = json_encode($res->content);
        
        // Encode TEXT content
        else $res->content = str_replace("\r\n", "<br>", $res->content);
        
        // Mask credentials
        if (isset($res->headers->Authorization)) $res->headers->Authorization = 'Basic *************';
        
        return $res;
    }
    
    /**
     * Send the request and return the response.
     */
    public function send($testCase = null)
    {
        $this->http->testCase($testCase);
        switch($this->method) {
            
            // GET request
            case 'GET' :
                $response = $this->http->get($this->url, $this->query, $this->headers);
                break;
            
            // POST request
            case 'POST' :
                if ($this->json)
                    $response = $this->http->postJson($this->url, $this->content, $this->query, $this->headers);
                else
                    $response = $this->http->post($this->url, $this->content, $this->query, $this->headers);
                break;
            
            // PUT request
            case 'PUT' :
                if ($this->json)
                    $response = $this->http->putJson($this->url, $this->content, $this->query, $this->headers);
                else
                    $response = $this->http->put($this->url, $this->content, $this->query, $this->headers);
                break;
            
            // DELETE request
            case 'DELETE' :
                $response = $this->http->delete($this->url, $this->query, $this->headers);
                break;
            
        }
        $this->clear();
        return $response;
    }

    /**
     * Clean settings.
     */
    public function clear()
    {
        $this->headers = [];
        $this->query = [];
        $this->content = '';
        return $this;
    }
    
    /**
     * Get headers.
     */
    protected function headers($include = [])
    {
        $res = [];

        // Prefered lang
        if ($this->lang && in_array('lang', $include))
            $res['Accept-Language'] = config('trax-xapi-client.xapi.lang');
        
        // xAPI version
        if ($this->version)
            $res['X-Experience-API-Version'] = config('trax-xapi-client.xapi.version');
        
        // Authentication (BasicHTTP)
        if ($this->authentication) {
            $username = config('trax-xapi-client.lrs.key');
            $password = config('trax-xapi-client.lrs.secret');
            $res['Authorization'] = "Basic " . base64_encode($username . ":" . $password);
        }
        return $res;
    }

    /**
     * Include testing inputs to the request.
     */
    protected function testing($testing = [])
    {
        if (isset($testing['query'])) $this->query = array_merge($this->query, $testing['query']);
        if (isset($testing['remove-query'])) $this->query = array_diff_key($this->query, array_flip($testing['remove-query']));
        if (isset($testing['headers'])) $this->headers = array_merge($this->headers, $testing['headers']);
        if (isset($testing['remove-headers'])) $this->headers = array_diff_key($this->headers, array_flip($testing['remove-headers']));
    }

}
