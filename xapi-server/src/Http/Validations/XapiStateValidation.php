<?php

namespace Trax\XapiServer\Http\Validations;

trait XapiStateValidation
{
    use XapiDocumentValidation;

    
    /**
     * Document specific identifier.
     */
    protected $documentId = 'stateId';
    
    /**
     * Document specific identifier.
     */
    protected $documentFields = ['activity', 'agent', 'registration'];
    
}
