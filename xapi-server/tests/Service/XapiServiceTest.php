<?php

namespace Trax\XapiServer\Tests\Service;

use Tests\TestCase;

class XapiServiceTest extends TestCase
{
    /**
     * Remote testing: use classic HTTP requests to access a remote LRS.
     * Local testing: use local TestCase requests to test the current LRS.
     */
    protected $remote = false;

    /**
     * Test context. Passed to the client.
     */
    protected $context;


    /**
     * Test case setup.
     */
    public function setUp()
    {
        parent::setUp();
        if (!$this->remote) $this->context = $this;
    }

    /**
     * Get response (needed for Lumen)
     */
    public function getResponse()
    {
        return $this->response;
    }
    
    
}
