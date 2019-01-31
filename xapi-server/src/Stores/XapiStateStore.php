<?php

namespace Trax\XapiServer\Stores;

class XapiStateStore extends StateStore
{
    use XapiDocumentStore;


    /**
     * Document specific identifier.
     */
    protected $documentId = 'stateId';

    /**
     * Document specific identifier.
     */
    protected $documentFields = ['activity', 'agent', 'registration'];
    
    
}
