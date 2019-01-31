<?php

namespace Trax\XapiServer\Http\Validations;

trait XapiAgentProfileValidation
{
    use XapiProfileValidation;

    
    /**
     * Document specific identifier.
     */
    protected $documentId = 'profileId';
    
    /**
     * Document specific identifier.
     */
    protected $documentFields = ['agent'];
    
}
