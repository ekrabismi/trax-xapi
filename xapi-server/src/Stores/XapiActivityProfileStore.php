<?php

namespace Trax\XapiServer\Stores;

class XapiActivityProfileStore extends ActivityProfileStore
{
    use XapiDocumentStore;


    /**
     * Document specific identifier.
     */
    protected $documentId = 'profileId';

    /**
     * Document specific identifier.
     */
    protected $documentFields = ['activity'];
    
    
}
