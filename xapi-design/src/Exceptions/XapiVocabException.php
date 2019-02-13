<?php

namespace Trax\XapiDesign\Exceptions;

use Exception;

class XapiVocabException extends Exception
{
    /**
     * Default message
     */
    protected $defaultMessage = 'xAPI Vocab Exception';


    /**
     * Create a new exception.
     */
    public function __construct($message = null)
    {
        // Message
        if (isset($message)) $message = $this->defaultMessage.': '.$message;
        else $message = $this->defaultMessage;
        
        parent::__construct($message);
    }

    
}
