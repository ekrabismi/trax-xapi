<?php

namespace Trax\XapiServer\Stores;

class XapiActivityStore extends ActivityStore
{
    use XapiStore;
    
    
    /**
     * Store a data.
     */
    public function store($data, $options = array())
    {
        // Normalize input data
        $data = $this->normalizedJsonData($data);
        $data['objectType'] = 'Activity';
        
        // Check if the activity exists
        try {
            $record = $this->findBy('data.id', $data['id']);
        } catch (\Exception $e) {
            
            // Create the activity
            return parent::store($data);
        }
        
        // Merge activities
        if (isset($data['definition'])) {
            $merged = $this->normalizedJsonData($record->data);
            if (!isset($merged['definition'])) $merged['definition'] = array();
            foreach($data['definition'] as $prop => $val) {
                if (in_array($prop, ['name', 'description']) && isset($merged['definition'][$prop])) {
                    $merged['definition'][$prop] = array_merge($merged['definition'][$prop], $val);
                } else {
                    $merged['definition'][$prop] = $val;
                }
            }
            return parent::update($record->id, $merged);
        }
    }
    
    /**
     * Find a data entry, given its id.
     */
    public function getOne($activityId)
    {
        $records = $this->getRequest($activityId);
        
        // No result
        if ($records->isEmpty($records)) {
            return (object)array('objectType' => 'Activity', 'id' => $activityId);
        }
        
        // Result
        return $records[0]->data;
    }
    
    /**
     * Get data entries.
     */
    protected function getRequest($activityId)
    {
        $search = array();
        $search['data.id'] = $activityId;
        $args['search'] = $search;
        return parent::get($args);
    }
    
}
