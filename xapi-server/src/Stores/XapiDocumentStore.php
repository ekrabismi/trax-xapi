<?php

namespace Trax\XapiServer\Stores;

use Trax\XapiServer\Exceptions\XapiRequestException;
use Trax\DataStore\Exceptions\NotFoundException;

trait XapiDocumentStore
{
    use XapiStore;


    /**
     * Updated value.
     */
    protected $documentUpdated;

    
    /**
     * Get data entries.
     */
    public function get($args = array(), $options = array())
    {
        return $this->getRequest($args, $options)->map(function($record) {
            $documentId = $this->documentId;
            return $record->data->$documentId;
        });
   }
    
    /**
     * Find a data entry, given its id.
     */
    public function getOne($id, $options = array())
    {
        // Request
        $options[$this->documentId] = $id;
        $records = $this->getRequest([], $options);
        
        // No result
        if ($records->isEmpty($records))
            throw new NotFoundException('Record not found.');
        
        // Result
        return $records[0];
    }
    
    /**
     * Delete a data entry, given its id.
     */
    public function deleteOne($id, $options = array())
    {
        try {
            $record = $this->getOne($id, $options);
            return parent::delete($record->id);
        } catch(\Exception $e) {
        }
    }
    
    /**
     * Delete a data entry, given its id.
     */
    public function deleteAll($options = array())
    {
        $records = $this->getRequest([], $options);
        foreach($records as $record) {
            parent::delete($record->id);
        }
        return true;
    }
    
    /**
     * Store a data.
     */
    public function store($data, $options = array())
    {
        // Testing data
        $data = $this->parseTestingData($data);
        
        // Get record
        try {
            $record = $this->getOne($options[$this->documentId], $options);
        } catch (\Exception $e) {
            return $this->storeNew($data, $options);
        }
        return $this->storeUpdate($record, $data, $options);
    }
    
    /**
     * Store a new data.
     */
    protected function storeNew($data, $options)
    {
        $data = array(
                    $this->documentId => $options[$this->documentId],
                    'document' => $data,
                    'contentType' => $options['contentType'],
                    'updated' => $this->documentUpdated,
                );
        
        // Activity
        if (in_array('activity', $this->documentFields))
            $data['activityId'] = $options['activityId'];
        
        // Agent
        if (in_array('agent', $this->documentFields))
            $data['agent'] = json_decode($options['agent'], true);

        // Registration
        if (in_array('registration', $this->documentFields)) {
            if (isset($options['registration'])) $data['registration'] = $options['registration'];
            else $data['registration'] = '';
        }
        return parent::store($data);
    }
    
    /**
     * Store a new data.
     */
    protected function storeUpdate($record, $data, $options)
    {
        $oldData = json_decode(json_encode($record->data), true);
        $canMerge = isset($options['canMerge']) && $options['canMerge'];
        $shouldMerge = $canMerge && ($oldData['contentType'] == 'application/json' || $options['contentType'] == 'application/json');
        
        // Incompatible types for JSON merge
        if ($shouldMerge && $oldData['contentType'] != $options['contentType'])
            throw new XapiRequestException('JSON content can not be merged because one content type is not JSON.');
            
        // Merge JSON data
        if ($shouldMerge) {
            $oldData['document'] = array_merge($oldData['document'], $data);
        } else {
            
            // Or replace data
            $oldData['document'] = $data;
            $oldData['contentType'] = $options['contentType'];
        }
        $oldData['updated'] = $this->documentUpdated;
        return parent::update($record->id, $oldData);
    }
    
    /**
     * Parse testing data.
     */
    protected function parseTestingData($data)
    {
        $this->documentUpdated = traxIsoTimestamp();
        if (isset($data['_test'])) {
            if (isset($data['_test']['updated'])) $this->documentUpdated = $data['_test']['updated'];
            unset($data['_test']);
        }
        return $data;
    }
    
    /**
     * Get data entries.
     */
    protected function getRequest($args = array(), $options = array())
    {
        $search = array();
        
        // StateId / ProfileId
        if (isset($options[$this->documentId])) $search['data.'.$this->documentId] = $options[$this->documentId];
        
        // Activity
        if (in_array('activity', $this->documentFields))
            $search['data.activityId'] = $options['activityId'];
        
        // Agent
        if (in_array('agent', $this->documentFields)) {
            $agent = json_decode($options['agent'], true);
            $agentSearch = array();
            $agentSearch[] = $this->getAgentSearch($agent, 'data.agent');
            $search[] = $agentSearch;
        }
        
        // Registration
        if (in_array('registration', $this->documentFields)) {
            if (isset($options['registration'])) $search['data.registration'] = $options['registration'];
            else $search['data.registration'] = '';
        }
        
        // Since
        if (isset($options['since'])) $args['since'] = $options['since'];

        // Request
        $args['search'] = $search;
        return parent::get($args);
    }
    
    /**
     * Filtering by date: since.
     */
    protected function since($builder, $isoDateString, $prop = 'created_at')
    {
        return parent::since($builder, $isoDateString, 'data.updated');
    }
    

}
