<?php

namespace Trax\XapiServer\Http\Middleware;

use Trax\XapiServer\Exceptions\XapiHeaderException;

class XapiMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle($request, $next, $guard = null)
    {
        // Before actions: check headers
        $this->checkVersionHeader($request);
        
        // Fine, we continue
        $response =  $next($request);
        
        // After actions: add headers
        $response->header('X-Experience-API-Version', '1.0.3');
        
        return $response;
    }

    /**
     * Check X-Experience-API-Version header.
     */
    public function checkVersionHeader($request)
    {
        // No version
        if (!traxHasHeader($request, 'X-Experience-API-Version')) 
            throw new XapiHeaderException('Missing X-Experience-API-Version header.');

        // Wrong format
        $version = traxHeader($request, 'X-Experience-API-Version');
        if (!traxValidate($version, 'xapi_version'))
            throw new XapiHeaderException('Incorrect X-Experience-API-Version header.');
    }

}
