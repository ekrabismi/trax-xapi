<?php

namespace Trax\XapiServer\Stores;

use Trax\XapiServer\XapiStatementFormatter;
use Trax\XapiServer\Exceptions\XapiRequestException;
use Trax\DataStore\Exceptions\NotFoundException;

class XapiStatementStore extends StatementStore
{
    use XapiStore;


    /**
     * Get data entries.
     */
    public function get($args = array(), $options = array())
    {            
        if ((isset($args['limit']) && $args['limit'] > 0)
            || (!isset($args['agent']) && !isset($args['verb']) && !isset($args['activity']) && !isset($args['registration']))
            ) {
            
            // Request with limit or without filter

            // Always fix a limit 
            if (!isset($args['limit']) || $args['limit'] == 0) $args['limit'] = config('trax-xapi-server.api.limit');

            // Get non-voided statements
            $records = $this->getRequest($args, $options);

        } else {
            
            // Request without limit or with filters

            // Always fix a limit 
            if (!isset($args['limit']) || $args['limit'] == 0) $args['limit'] = config('trax-xapi-server.api.limit');

            // Get all statements, including voided
            $records = $this->getRequest($args, $options, true);

            // Keep their IDs
            $targetedIds = $records->map(function ($record) {
                return $record->data->id;
            });

            // Remove voided statements from result
            $records = $records->where('voided', false);
            
            // Next rounds
            while (!$targetedIds->isEmpty()) {
                                
                // Find targeting records
                $targetingRecords = $this->getTargetingRequest($targetedIds);

                // Keep their IDs
                $targetedIds = $targetingRecords->map(function ($record) {
                    return $record->data->id;
                });
                
                // Keep the records
                $records =  $this->mergeCollections($records, $targetingRecords);
           }
           
            // Keep unique records only
            $records = $records->unique('id');
        }
        
        // Format output data
        $formatter = new XapiStatementFormatter();        
        $last = $records->last();
        $statements = $records->transform(function ($item) use ($formatter, $args, $options) {
            $format = isset($args['format']) ? $args['format'] : 'exact';
            $lang = isset($options['lang']) ? $options['lang'] : null;
            return $formatter->format($item->data, $format, $lang);
        });
        
        // Prepare result
        $result = (object)array();
        
        // Create a 'more' link
        if (isset($args['limit']) && $args['limit'] > 0 && $args['limit'] == count($statements) - 1) {
            $statements = $statements->slice(0, -1);
            $query = $args;
            unset($query['order-by']);
            unset($query['order-dir']);
            unset($query['search']);
            $query['from'] = $last->id;
            $result->more = $options['url'].'?'.http_build_query($query);
        }

        // Return an object
        $result->statements = $statements->values()->all();
        return $result;
    }
    
    /**
     * Get targeting statements.
     */
    protected function getTargetingRequest($targetedIds)
    {
        $search = array();

        // Only SubStatements
        $search['data.object.objectType'] = 'StatementRef'; 
        
        // With matching id
        $search['data.object.id'] = (object)array(
            'operator' => 'IN',
            'value' => $targetedIds
        );
        $args['search'] = $search;
        
        // Request
        return parent::get($args);
    }
    
    /**
     * Get request.
     */
    protected function getRequest($args = array(), $options = array(), $includeVoided = false)
    {
        // Parent args
        $parentArgs = array();
        
        // Only non voided statements
        $search = array();
        if (!$includeVoided) $search['voided'] = false;

        // Ascending filter
        //$args['order-by'] = 'data.stored'; Because it's faster with the ID
        $args['order-by'] = 'id';
        $args['order-dir'] = (isset($args['ascending']) && traxBool($args['ascending'])) ? 'asc' : 'desc';
        
        // Registration
        if (isset($args['registration'])) $search['data.context.registration'] = $args['registration'];
        
        // Agent
        if (isset($args['agent'])) {
            $agent = json_decode($args['agent'], true);
            $agentSearch = array();
            
            // Standard search
            $agentSearch[] = $this->getAgentSearch($agent, 'data.actor');
            
            // Related search
            if (isset($args['related_agents']) && traxBool($args['related_agents'])) {
                $agentSearch[] = $this->getAgentSearch($agent, 'data.object');
                $agentSearch[] = $this->getAgentSearch($agent, 'data.authority');
                $agentSearch[] = $this->getAgentSearch($agent, 'data.authority.member[*]');
                $agentSearch[] = $this->getAgentSearch($agent, 'data.context.instructor');
                $agentSearch[] = $this->getAgentSearch($agent, 'data.context.team');
                $agentSearch[] = $this->getAgentSearch($agent, 'data.object.actor');
                $agentSearch[] = $this->getAgentSearch($agent, 'data.object.object');
                $agentSearch[] = $this->getAgentSearch($agent, 'data.object.context.instructor');
                $agentSearch[] = $this->getAgentSearch($agent, 'data.object.context.team');                
            }
                
            $search[] = $agentSearch;
        }
        
        // Verb
        if (isset($args['verb'])) $search['data.verb.id'] = $args['verb'];
        
        // Activity
        if (isset($args['activity'])) {
            $activitySearch = array();

            // Standard search
            $activitySearch[] = [
                //'data.object.objectType' => 'Activity',  // Don't do that because objectType is optional when the value is Activity at this place of the Statement
                'data.object.id' => $args['activity'],
            ];
            
            // Related search
            if (isset($args['related_activities']) && traxBool($args['related_activities'])) {
                
                // Search in sub statement
                $activitySearch[] = [
                    'data.object.objectType' => 'SubStatement',
                    'data.object.object.objectType' => 'Activity',
                    'data.object.object.id' => $args['activity'],
                ];
                
                // Search in context activities
                $activitySearch[] = ['data.context.contextActivities.parent[*].id' => $args['activity']];
                $activitySearch[] = ['data.context.contextActivities.grouping[*].id' => $args['activity']];
                $activitySearch[] = ['data.context.contextActivities.category[*].id' => $args['activity']];
                $activitySearch[] = ['data.context.contextActivities.other[*].id' => $args['activity']];
            } 

            $search[] = $activitySearch;
        }
        
        // From (more statements)
        if (isset($args['from'])) {
            $operator = ($args['order-dir'] == 'asc') ? '>=' : '<=';  
            $search['id'] = (object)array('operator'=>$operator, 'value'=>$args['from']);
        }

        // Search
        if (!empty($search)) $args['search'] = $search;

        // Limit: take one more statement to see if a more link is needed
        if (isset($args['limit']) && $args['limit'] > 0) $args['limit']++;

        // Request
        return parent::get($args, $options);
    }
    
    
    /**
     * Find a data entry, given a unique field.
     */
    public function findBy($field, $value, $options = array())
    {
        // Get statement
        $statement = parent::findBy($field, $value, $options);
        
        // Check the voided status
        if ((!isset($options['voided']) && $statement->voided)
                || (isset($options['voided']) && $options['voided'] != $statement->voided)) {
            throw new NotFoundException('The requested record has not the right voided status.');
        }

        // Prepare output
        $formatter = new XapiStatementFormatter();
        $format = isset($options['format']) ? $options['format'] : 'exact';
        $lang = isset($options['lang']) ? $options['lang'] : null;
        return $formatter->format($statement->data, $format, $lang);
    }
    
    /**
     * Store a single data given its ID.
     */
    public function storeOne($id, $data, $options = array())
    {
        // Normalize input data
        $data = $this->normalizedJsonData($data);
        
        // Inject ID into the data
        $data['id'] = $id;
        
        // Store it
        return $this->store($data, $options);
    }
    
    /**
     * Get consistent through timestamp.
     */
    public function consistentThrough()
    {
        return traxIsoTimestamp();
    }
    
    /**
     * Prepare a data before recording it: pre-processing.
     */
    protected function dataInputPre($data, $options, $model = null)
    {
        $data = parent::dataInputPre($data, $options, $model);
        
        // Set the stored prop
        $data['stored'] = traxIsoTimestamp();

        // Set the authority prop
        $data['authority'] = [
            'objectType' => 'Agent', 
            'account' => config('trax-xapi-server.authority')
        ];
        
        // Testing data (override stored)
        if (isset($data['_test'])) {
            if (isset($data['_test']['stored'])) $data['stored'] = $data['_test']['stored'];
            if (isset($data['_test']['authority'])) $data['authority'] = $data['_test']['authority'];
            unset($data['_test']);
        }

        // Set the voided prop
        if (!isset($data['voided'])) $data['voided'] = false;

        // Set the UUID prop
        if (!isset($data['id'])) $data['id'] = traxUuid();

        // Set the timestamp prop
        if (!isset($data['timestamp'])) $data['timestamp'] = $data['stored'];
        
        // Set the version prop
        if (!isset($data['version'])) $data['version'] = '1.0.0';

        // Transform single contextActivities into arrays
        if (isset($data['context']) && isset($data['context']['contextActivities']))
            $data['context']['contextActivities'] = $this->contextActivities($data['context']['contextActivities']);

        // Transform single contextActivities into arrays
        if (isset($data['object']['objectType']) && $data['object']['objectType'] == 'SubStatement'
            && isset($data['object']['context']) && isset($data['object']['context']['contextActivities']))
                $data['object']['context']['contextActivities'] = $this->contextActivities($data['object']['context']['contextActivities']);

        // Voiding statement
        if ($data['verb']['id'] == 'http://adlnet.gov/expapi/verbs/voided')
            $this->voidStatement($data);

        return $data;
    }

    /**
     * Normalize contextActivities (transform single objects to arrays).
     */
    protected function contextActivities($contextActivities)
    {
        if (isset($contextActivities['parent']) && $this->associativeArray($contextActivities['parent'])) {
            $contextActivities['parent'] = array($contextActivities['parent']);
        }
        if (isset($contextActivities['grouping']) && $this->associativeArray($contextActivities['grouping'])) {
            $contextActivities['grouping'] = array($contextActivities['grouping']);
        }
        if (isset($contextActivities['category']) && $this->associativeArray($contextActivities['category'])) {
            $contextActivities['category'] = array($contextActivities['category']);
        }
        if (isset($contextActivities['other']) && $this->associativeArray($contextActivities['other'])) {
            $contextActivities['other'] = array($contextActivities['other']);
        }
        return $contextActivities;
    }
    
    /**
     * Void a statement.
     */
    protected function voidStatement($data)
    {
        if ($data['object']['objectType'] == 'StatementRef') {
            
            // Get the statement
            $voidedUuid = $data['object']['id'];
            try {
                $voidedStatement = parent::findBy('data.id', $voidedUuid);
            } catch(\Exception $e) {
                return $data;
            }
            
            // Voiding statement can not be voided
            if ($voidedStatement->data->verb->id == 'http://adlnet.gov/expapi/verbs/voided')
                throw new XapiRequestException('Voiding statement can not be voided');
            
            // Void it
            $voidedStatement->data->voided = true;
            $this->update($voidedStatement->id, $voidedStatement->data);
        }
    }
    
    /**
     * Get the data entry ID to be returned.
     */
    protected function idOutput($id, $record)
    {
        // Be sure to have a data object
        if (is_string($record['data'])) $record['data'] = json_decode($record['data']);

        return $record['data']->id;
    }
    
    /**
     * Filtering by date: since.
     */
    protected function since($builder, $isoDateString, $prop = 'created_at')
    {
        return parent::since($builder, $isoDateString, 'data.stored');
    }
    
    /**
     * Filtering by date: until.
     */
    protected function until($builder, $isoDateString, $prop = 'created_at')
    {
        return parent::until($builder, $isoDateString, 'data.stored');
    }
    
    /**
     * Merge 2 collections.
     * Because ->merge function does not work with collections of MongoDB models
     */
    protected function mergeCollections($collection1, $collection2)
    {
        $array1 = $collection1->all();
        $array2 = $collection2->all();
        $merged = array_merge($array1, $array2);
        return collect($merged);
    }

    /**
     * Void a statement.
     */
    protected function associativeArray($array)
    {
        if (array() === $array) return false;
        return array_keys($array) !== range(0, count($array) - 1);
    }
        
}
