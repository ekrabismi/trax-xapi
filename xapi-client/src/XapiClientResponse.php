<?php

namespace Trax\XapiClient;

use Symfony\Component\HttpFoundation\Response;

class XapiClientResponse
{
    /**
     * Status code.
     */
    protected $code;
    
    /**
     * Phrase.
     */
    protected $phrase;
    
    /**
     * Headers.
     */
    protected $headers = array();
    
    /**
     * Content.
     */
    protected $content;
    
    /**
     * Visible headers.
     */
    protected $visibleHeaders = [
        'X-Experience-API-Version',
        'X-Experience-API-Consistent-Through',
        'Content-Type',
        'Content-Length',
        'ETag'
    ];   
    
    /**
     * Construct.
     */
    public function __construct($response)
    {
        if (get_class($response) == 'GuzzleHttp\Psr7\Response') {
            $this->initGuzzle($response);
        } else if (get_class($response) == 'Illuminate\Foundation\Testing\TestResponse') {
            $this->initLaravelTesting($response);
        } else {
            $this->initLumenTesting($response);
        }
    }

    /**
     * Init with Guzzle response.
     */
    protected function initGuzzle($response)
    {
        $this->code = $response->getStatusCode();
        $this->phrase = $response->getReasonPhrase();
        $this->processHeaders($response->getHeaders());
        $this->processContent($response->getBody());
    }

    /**
     * Init with Laravel Testing response.
     */
    protected function initLaravelTesting($response)
    {
        $this->code = $response->getStatusCode();
        $this->phrase = Response::$statusTexts[$this->code];
        $this->processHeaders($response->headers->all());
        $this->processContent($response->getContent());
    }

    /**
     * Init with Lumen Testing response.
     */
    protected function initLumenTesting($response)
    {
        $isResponse = ($response instanceof Response);
        if (!$isResponse) $response = $response->getResponse();
        $this->initLaravelTesting($response);
    }

    /**
     * JSON response.
     */
    public function json()
    {
        $res = (object)array(
            'code' => $this->code,
            'phrase' => $this->phrase,
            'headers' => (object)$this->headers,
            'content' => $this->content,
        );
        if (isset($res->content)) {

            // Encode JSON content
            if (!is_string($res->content)) $res->content = json_encode($res->content);
            
            // Encode TEXT content
            else $res->content = str_replace("\r\n", "<br>", $res->content);

        } else {
            unset($res->content);
        }
        return $res;
    }

    /**
     * Headers processing.
     */
    protected function processHeaders($headers)
    {
        $lcVisibleHeaders = array_map('strtolower', $this->visibleHeaders);
        foreach($headers as $name => $values) {
            $lcName = strtolower($name);
            $key = array_search($lcName, $lcVisibleHeaders);
            if ($key !== false) {
                $fcName = $this->visibleHeaders[$key];
                $this->headers[$fcName] = implode(', ', $values);
            }
        }
    }

    /**
     * Content processing.
     */
    protected function processContent($body)
    {
        // JSON vs String
        if (isset($this->headers['Content-Type']) && strpos($this->headers['Content-Type'], 'application/json') !== false) {
            $this->content = json_decode($body);
        } else {
            $this->content = (string)$body;
        }
        
        // Remove content when required
        if (!$this->content || $this->code == 204) $this->content = null;
    }

}
