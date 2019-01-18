<?php

namespace Trax\XapiServer\Tests\Service;

class XapiSampleServiceTest extends XapiServiceTest
{
    public function test_client_get()
    {
        // When we get statements
        $response = \XapiClient::statements()->get([
            'statementId' => 'd53862ed-178b-3bf0-9a7c-9db04e3e53de',
            'attachments' => true
        ])->send($this->context);

        // We get a 200 code
        $this->assertTrue($response->code == 200);
    }
    
    public function test_client_post()
    {
        // Given a statement
        $statement = [
            'actor' => ['mbox' => 'mailto:learner1@xapi.fr'],
            'verb' => ['id' => 'http://adlnet.gov/expapi/verbs/completed'],
            'object' => ['id' => 'http://xapi.fr/activities/act01'],
        ];

        // When we post a statement
        $response = \XapiClient::statements()->post($statement)->send($this->context);

        // We get a 200 code
        $this->assertTrue($response->code == 200);
    }

    public function test_builder_post()
    {
        // Given a Statement without Agent
        $response = \XapiDesign::builder()
            ->agent('sebastien@fraysse.eu')
            ->verb('completed')
            ->activity('act1')
            ->post($this->context);

        // I get a 200 code
        $this->assertTrue($response->code == 200);
    }

    public function test_pattern()
    {
        $passed = \XapiDesign::profile()->pattern('statement-get-attachments')->test()->assert($this->context);
        $this->assertTrue($passed);
    }
    
    public function test_statements()
    {
        $passed = \XapiDesign::profile()->statement('errors-statement')->test()->assert($this->context);
        $this->assertTrue($passed);
    }

    
}