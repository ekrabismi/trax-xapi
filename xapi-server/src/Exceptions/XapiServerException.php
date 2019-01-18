<?php

namespace Trax\XapiServer\Exceptions;

use Trax\Foundation\Exceptions\CustomException;

class XapiServerException extends CustomException
{
    /**
     * Default message
     */
    protected $defaultMessage = 'xAPI Exception';

    /**
     * HTTP headers
     */
    protected $httpHeaders = ['X-Experience-API-Version' => '1.0.3'];

}
