<?php

namespace Trax\XapiServer\Http\Middleware;

use Trax\XapiServer\Exceptions\XapiHeaderException;
use Trax\XapiServer\Exceptions\XapiRequestException;
use Trax\XapiServer\Exceptions\XapiContentException;
use Trax\DataStore\Http\Requests\MultipartRequest;

class XapiStatementMiddleware
{
    use MultipartRequest;
    
        
    /**
     * Handle an incoming request.
     */
    public function handle($request, $next, $guard = null)
    {
        // Alternate request
        if ($this->checkAlternateRequest($request)) return $next($request);

        // Header: Content-Type
        $this->checkContentTypeHeader($request);
        
        // JSON content
        $statements = $this->checkJsonContent($request);
        
        // Multipart content
        if (!$statements) $this->checkMultipartContent($request);

        // Fine, we continue
        return $next($request);
    }

    /**
     * Check alternate request.
     */
    protected function checkAlternateRequest($request)
    {
        if ($request->has('method')) {
            
            // Check that there is only the 'method' param in the query string
            $query = $request->query();
            if (count($query) > 1 || !isset($query['method']))
                throw new XapiRequestException('Unknown param(s) in query string of an alternate request.');
            
            // Accepted methods without more check
            if ($request->input('method') == 'GET') return true;
            
            // Accepted methods with more checks
            if ($request->input('method') == 'PUT') return false;
            
            // Refused
            throw new XapiRequestException('Alternate method not accepted: '.$request->input('method').'.');
        }
    }

    /**
     * Check Content-Type header.
     */
    protected function checkContentTypeHeader($request)
    {
        // No content type
        if (!traxHasHeader($request, 'Content-Type'))
            throw new XapiHeaderException('Missing Content-Type header.');
            
        // Check header
        $contentType = traxHeader($request, 'Content-Type');
        if ($contentType != 'application/json' && !$this->isMultipart($request)) {
            
            // Last chance: it could be an alternative request with content-type x-www-form-urlencoded
            if (!$request->has('method') || strpos($contentType, 'application/x-www-form-urlencoded') === false)
                throw new XapiHeaderException('Content-Type is neither application/json nor multipart/mixed: '.$contentType.'.');
        }
    }
    
    /**
     * Check JSON content.
     */
    protected function checkJsonContent($request)
    {
        // Not a JSON content
        $contentType = traxHeader($request, 'Content-Type');
        if ($contentType != 'application/json' && !$request->has('method')) return false;
        
        // Missing content
        if (!traxHasContent($request)) 
            throw new XapiContentException('Missing JSON content.');
        
        // Get content
        $content = traxContent($request);
        
        // Not a valid JSON content
        if (!json_encode($content))
            throw new XapiContentException('Invalid JSON content.');

        // Return an object, not an associative array
        return true;
    }
    
    /**
     * Check multipart content.
     */
    protected function checkMultipartContent($request)
    {
        $parts = $this->parts($request);
        
        // Invalid content
        if (empty($parts))
            throw new XapiContentException('Invalid multipart content.');
        
        // Statements content-type
        $statements = array_shift($parts);
        if (!isset($statements->contentType) || $statements->contentType != 'application/json') {
            throw new XapiContentException('Content-Type is not application/json in statements part.');
        }
        
        // Statements JSON validity
        $statements = json_decode($statements->content);
        if (!$statements) 
            throw new XapiContentException('Invalid JSON content in statements part.');
        
        // Check attachments
        foreach($parts as $attachment) {
            
            // Check hash
            if (!isset($attachment->sha2))
                throw new XapiContentException('Missing X-Experience-API-Hash in attachment part.');
            
            // Check encoding
            if (!isset($attachment->encoding))
                throw new XapiContentException('Missing Content-Transfer-Encoding in attachment part.');
            
            if ($attachment->encoding != 'binary')
                throw new XapiContentException('Wrong Content-Transfer-Encoding in attachment part. Must be binary.');
        }
    }

}

