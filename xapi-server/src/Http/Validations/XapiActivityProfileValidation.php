<?php

namespace Trax\XapiServer\Http\Validations;

trait XapiActivityProfileValidation
{
    use XapiProfileValidation;

    
    /**
     * Document specific identifier.
     */
    protected $documentId = 'profileId';
    
    /**
     * Document specific identifier.
     */
    protected $documentFields = ['activity'];
    
}
