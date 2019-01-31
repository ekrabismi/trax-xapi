<?php

namespace Trax\XapiClient;

class XapiStatementApi extends XapiRemoteApi
{    
    /**
     * CRLF
     */
    protected $crlf = "\r\n";

    /**
     * Boundary
     */
    protected $boundary = "___XAPI_MULTIPART_SECTION___";

    
    /**
     * GET request.
     */
    public function get($args = [])
    {
        return $this->httpGet($args);
    }
    
    /**
     * PUT request: must provide the statement UUID.
     */
    public function put($content, $statementId = null, $testing = [])
    {
        $this->httpPut($content);

        // Statement ID
        if (isset($statementId)) $uuid = $statementId;
        else if (isset($content['id'])) $uuid = $content['id'];
        else $uuid = $this->faker->uuid;
        $this->query = array('statementId' => $uuid);

        // Testing
        $this->testing($testing);

        return $this;
    }
    
    /**
     * POST request: may have attachments.
     */
    public function post($content, $attachments = [], $testing = [])
    {
        if (empty($attachments)) {

            // No attachment
            $this->httpPost($content);

        } else {

            // Multipart content
            $partTesting = (isset($testing['multipart']) && isset($testing['multipart'][0])) ? $testing['multipart'][0] : null;
            $this->content = $this->jsonPart($content, $partTesting);
            $i = 1;
            foreach ($attachments as $attachment) {
                $partTesting = (isset($testing['multipart']) && isset($testing['multipart'][$i])) ? $testing['multipart'][$i] : null;
                $this->content .= $this->attachmentPart($attachment, $partTesting);
                $i++;
            }
            $this->content .= $this->crlf.'--'.$this->boundary.'--'.$this->crlf;
            
            // Header
            $this->headers = array_merge([
                'Content-Type' => 'multipart/mixed; boundary="'.$this->boundary.'"',
                'Content-Length' => mb_strlen($this->content, '8bit'),
            ], $this->headers());
            
            // Others
            $this->method = 'POST';
            $this->query = [];
            $this->json = false;
        }

        // Testing
        $this->testing($testing);

        return $this;
    }
    
    /**
     * Get JSON part in a multipart content
     */
    protected function jsonPart($statements, $testing = [])
    {
        // Boundary
        $content = $this->crlf.'--'.$this->boundary.$this->crlf;

        // Headers
        $headers = ['Content-Type' => 'application/json'];
        if (isset($testing['headers'])) $headers = array_merge($headers, $testing['headers']);
        foreach($headers as $key => $val) {
            $content .= $key.':'.$val.$this->crlf;
        } 

        // Content
        $content .= $this->crlf.json_encode($statements);
        return $content;
    }
    
    /**
     * Get attachment part in a multipart content
     */
    protected function attachmentPart($attachment, $testing = [])
    {
        if (is_array($attachment)) $attachment = (object)$attachment;

        // Boundary
        $content = $this->crlf.'--'.$this->boundary.$this->crlf;

        // Headers
        $headers = [
            'Content-Length' => mb_strlen($attachment->content, '8bit'),
            'Content-Type' => $attachment->contentType,
            'Content-Transfer-Encoding' => 'binary',
            'X-Experience-API-Hash' => hash('sha256', $attachment->content),
        ];
        if (isset($testing['headers'])) $headers = array_merge($headers, $testing['headers']);
        foreach($headers as $key => $val) {
            $content .= $key.':'.$val.$this->crlf;
        } 

        // Content
        $content .= $this->crlf.$attachment->content;
        return $content;
    }
     

}
