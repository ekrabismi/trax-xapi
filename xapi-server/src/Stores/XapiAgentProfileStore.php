<?php

namespace Trax\XapiServer\Stores;

class XapiAgentProfileStore extends AgentProfileStore
{
    use XapiDocumentStore;


    /**
     * Document specific identifier.
     */
    protected $documentId = 'profileId';

    /**
     * Document specific identifier.
     */
    protected $documentFields = ['agent'];
    
    
}
