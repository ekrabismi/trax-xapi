<?php

namespace Trax\XapiDesign\Tests;

use Tests\TestCase;

class XapiDesignTest extends TestCase
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
     * Try to build a statement using a simple vocab index, and Post it.
     */
    public function test_builder_post()
    {
        // Given a Statement, when I post it
        $response = \XapiDesign::builder()->vocab(new XapiVocabExample())
            ->agent('sebastien@fraysse.eu')
            ->verb('verb1')
            ->activity('act1')
            ->post($this->context);

        // I get a 200 code
        $this->assertTrue($response->code == 200);
    }

    /**
     * Try to build a statement using a simple vocab index, and record it.
     */
    public function test_builder_record()
    {
        // Given a Statement, when I record it
        $response = \XapiDesign::builder()->vocab(new XapiVocabExample())
            ->agent('sebastien@fraysse.eu')
            ->verb('verb1')
            ->activity('act1')
            ->record();

        // I get an array
        $this->assertTrue(is_array($response));
    }
    
    
}
