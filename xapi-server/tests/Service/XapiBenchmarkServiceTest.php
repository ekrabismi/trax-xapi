<?php

namespace Trax\XapiServer\Tests\Service;

class XapiBenchmarkServiceTest extends XapiServiceTest
{

    /**
     * Benchmark size.
     */
    protected $size = 1000000;

    
    /**
     * Benchmark function. 
     * Adapt the .env to target different LRSs.
     */
    public function test_benchmark_client()
    {
        for ($i=0; $i < $this->size; $i++) {
            $statement = [
                'actor' => ['mbox' => 'mailto:learner1@xapi.fr'],
                'verb' => ['id' => 'http://adlnet.gov/expapi/verbs/completed'],
                'object' => ['id' => 'http://xapi.fr/activities/act01'],
            ];
            $response = \XapiClient::statements()->post($statement)->send($this->context);

            $this->assertTrue($response->code == 200);
            fwrite(STDERR, print_r('.', TRUE));
        }
    }
    
    /**
     * Benchmark function. 
     * Adapt the .env to target different LRSs.
     */
    public function test_benchmark_builder()
    {
        for ($i=0; $i < $this->size; $i++) {
            $passed = \XapiDesign::profile()->statement('actor-mbox')->test()->assert();
            $this->assertTrue($passed);
            fwrite(STDERR, print_r('.', TRUE));
        }
    }
    
    /**
     * Seeder function. 
     */
    public function test_seed()
    {
        $verbs = ['attempted', 'completed', 'passed', 'failed'];
        $faker = \Faker\Factory::create();
        $builder = \XapiDesign::builder();
        $store = \XapiServer::xapiStatements();

        for ($i=0; $i < $this->size; $i++) {
            $statement = $builder
                ->agent($faker->email)
                ->verb($verbs[rand(0, 3)])
                ->activity($faker->randomNumber())
                ->get();
            $store->store($statement);
            //fwrite(STDERR, print_r('.', TRUE));
        }
        $this->assertTrue(true);
    }
    
    
}
