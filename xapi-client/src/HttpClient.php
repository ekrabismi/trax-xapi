<?php

namespace Trax\XapiClient;

use Illuminate\Support\Str;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class HttpClient
{
    /**
     * Testing.
     */
    protected $testCase;    

    /**
     * HTTP service.
     */
    protected $guzzle;    

    
    /**
     * Construct.
     */
    public function __construct()
    {
        $this->guzzle = new Client();
    }

    /**
     * Switch to test mode
     */
    public function testCase($testCase = null)
    {
        $this->testCase = $testCase;
    }
    
    /**
     * GET request
     */
    public function get($url, $query = [], $headers = [])
    {
        if (!isset($this->testCase)) {

            // Guzzle
            try {
                $response = $this->guzzle->get($url, [
                    'headers' => $headers,
                    'query' => $query,
                ]);

            } catch(GuzzleException $e) {
                $response = $e->getResponse();
            }
    
        } else {

            // Test Case
            $url = $url.'?'.http_build_query($query);
            $response = $this->testCase->get($url, $headers);

        }
        return (new XapiClientResponse($response))->json();
    }

    /**
     * POST request
     */
    public function post($url, $content, $query = [], $headers = [])
    {
        if (!isset($this->testCase)) {

            // Guzzle
            try {
                $response = $this->guzzle->post($url, [
                    'headers' => $headers,
                    'query' => $query,
                    'body' => $content,
                ]);
            } catch(GuzzleException $e) {
                $response = $e->getResponse();
            }
    
        } else {

            // Test Case
            $url = $url.'?'.http_build_query($query);
            $server = $this->transformHeadersToServerVars($headers);
            $response = $this->testCase->call('POST', $url, [], [], [], $server, $content);
            
        }
        return (new XapiClientResponse($response))->json();
    }

    /**
     * POST JSON request
     */
    public function postJson($url, $content, $query = [], $headers = [])
    {
        if (!isset($this->testCase)) {

            // Guzzle
            try {
                $response = $this->guzzle->post($url, [
                    'headers' => $headers,
                    'query' => $query,
                    'json' => $content,
                ]);
            } catch(GuzzleException $e) {
                $response = $e->getResponse();
            }
    
        } else {

            // Test Case
            $url = $url.'?'.http_build_query($query);
            $response = $this->testCase->json('POST', $url, $content, $headers);
        }
        return (new XapiClientResponse($response))->json();
    }

    /**
     * PUT request
     */
    public function put($url, $content, $query = [], $headers = [])
    {
        if (!isset($this->testCase)) {

            // Guzzle
            try {
                $response = $this->guzzle->put($url, [
                    'headers' => $headers,
                    'query' => $query,
                    'body' => $content,
                ]);
            } catch(GuzzleException $e) {
                $response = $e->getResponse();
            }
    
        } else {

            // Test Case
            $url = $url.'?'.http_build_query($query);
            $server = $this->transformHeadersToServerVars($headers);
            $response = $this->testCase->call('PUT', $url, [], [], [], $server, $content);
            
        }
        return (new XapiClientResponse($response))->json();
    }

    /**
     * PUT JSON request
     */
    public function putJson($url, $content, $query = [], $headers = [])
    {
        if (!isset($this->testCase)) {

            // Guzzle
            try {
                $response = $this->guzzle->put($url, [
                    'headers' => $headers,
                    'query' => $query,
                    'json' => $content,
                ]);
            } catch(GuzzleException $e) {
                $response = $e->getResponse();
            }
    
        } else {

            // Test Case
            $url = $url.'?'.http_build_query($query);
            $response = $this->testCase->json('PUT', $url, $content, $headers);
        }
        return (new XapiClientResponse($response))->json();
    }

    /**
     * DELETE request
     */
    public function delete($url, $query = [], $headers = [])
    {
        if (!isset($this->testCase)) {

            // Guzzle
            try {
                $response = $this->guzzle->delete($url, [
                    'headers' => $headers,
                    'query' => $query,
                ]);
            } catch(GuzzleException $e) {
                $response = $e->getResponse();
            }
    
        } else {

            // Test Case
            $url = $url.'?'.http_build_query($query);
            $response = $this->testCase->delete($url, [], $headers);
            
        }
        return (new XapiClientResponse($response))->json();
    }


    //---------------------------- Functions copied from MakeHttpRequest because protected ------------------------//

    /**
     * Transform headers array to array of $_SERVER vars with HTTP_* format.
     */
    protected function transformHeadersToServerVars(array $headers)
    {
        return collect($headers)->mapWithKeys(function ($value, $name) {
            $name = strtr(strtoupper($name), '-', '_');
            return [$this->formatServerHeaderKey($name) => $value];
        })->all();
    }

    /**
     * Format the header name for the server array.
     */
    protected function formatServerHeaderKey($name)
    {
        if (! Str::startsWith($name, 'HTTP_') && $name != 'CONTENT_TYPE' && $name != 'REMOTE_ADDR') {
            return 'HTTP_'.$name;
        }
        return $name;
    }


}
