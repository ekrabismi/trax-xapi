<?php

namespace Trax\XapiServer\Tests\Service;

class XapiCoreServiceTest extends XapiServiceTest
{
    /**
     * Test all the statement samples of XapiDesign.
     */
    public function test_statements()
    {
        $this->runStatementTests();
    }

    /**
     * Test the Statement API.
     */
    public function test_statement_api()
    {
        $this->runApiTests('statement');
    }

    /**
     * Test the State API.
     */
    public function test_state_api()
    {
        $this->runApiTests('state');
    }

    /**
     * Test the Activity Profile API.
     */
    public function test_activity_profile_api()
    {
        $this->runApiTests('activityProfile');
    }

    /**
     * Test the Agent Profile API.
     */
    public function test_agent_profile_api()
    {
        $this->runApiTests('agentProfile');
    }

    /**
     * Test the Activity API.
     */
    public function test_activity_api()
    {
        $this->runApiTests('activity');
    }

    /**
     * Test the Agent API.
     */
    public function test_agent_api()
    {
        $this->runApiTests('agent');
    }

    /**
     * Test the About API.
     */
    public function test_about_api()
    {
        $this->runApiTests('about');
    }


    //-------------------------- PROTECTED ------------------------//


    protected function runStatementTests()
    {
        $codes = $this->statementCodes();
        $this->runStatements($codes);
    }

    protected function statementCodes($profile = 'core') 
    {
        $codes = [];
        $map = \XapiDesign::profile($profile)->statementsInfo();
        foreach ($map['children'] as $category => $cases) {
            foreach ($cases['children'] as $subcat => $case) {
                if (!isset($case['disabled']) || !$case['disabled']) {
                    $codes[] = $category.'-'.$subcat;
                }
            } 
        } 
        return $codes;
    }
    
    protected function runStatements($codes, $profile = 'core') 
    {
        foreach ($codes as $code) {
            $passed = \XapiDesign::profile($profile)->statement($code)->test()->assert($this->context);
            if (!$passed) dd('XapiDesign, Profile: '.$profile.' Statement: '. $code);
            $this->assertTrue($passed);
            fwrite(STDERR, print_r('.', TRUE));
        }
    }
    
    protected function runApiTests($api)
    {
        $codes = $this->patternCodes($api);
        $this->runPatterns($codes);
    }

    protected function patternCodes($api, $profile = 'core') 
    {
        $codes = [];
        $map = \XapiDesign::profile($profile)->patternInfo($api);
        foreach ($map['children'] as $method => $cases) {
            foreach ($cases['children'] as $operation => $case) {
                if (!isset($case['disabled']) || !$case['disabled']) {
                    $codes[] = $api.'-'.$method.'-'.$operation;
                }
            } 
        } 
        return $codes;
    }
    
    protected function runPatterns($codes, $profile = 'core') 
    {
        foreach ($codes as $code) {
            $passed = \XapiDesign::profile($profile)->pattern($code)->test()->assert($this->context);
            if (!$passed) dd('XapiDesign, Profile: '.$profile.' Pattern: '. $code);
            $this->assertTrue($passed);
            fwrite(STDERR, print_r('.', TRUE));
        }
    }
    
    
}
