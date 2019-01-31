<?php

namespace Trax\XapiServer\Exceptions;

use Exception;
use Trax\Account\Exceptions\AccountExceptionHandler;
use Trax\XapiServer\Exceptions\XapiStatementException;

class XapiServerExceptionHandler extends AccountExceptionHandler
{

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Exception $exception)
    {
        // Custom exceptions
        if ($exception instanceof XapiStatementException)
            return response($exception->getMessage(), 400);
        
        return parent::render($request, $exception);
    }


}
