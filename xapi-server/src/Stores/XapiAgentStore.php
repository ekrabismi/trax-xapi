<?php

namespace Trax\XapiServer\Stores;

class XapiAgentStore extends AgentStore
{
    use XapiStore;
    
    
    /**
     * Store a data.
     */
    public function store($data, $options = array())
    {
        // Normalize input data
        $data = $this->normalizedJsonData($data);

        // Store members
        if (isset($data['member'])) {
            foreach($data['member'] as $member) {
                $this->store($member);
            }
        }

        // Get Agent id
        $search = $this->getAgentSearch($data, 'data');
        if (empty($search)) return;

        // Force object type
        if (!isset($data['objectType'])) $data['objectType'] = 'Agent';
        
        // Check if the agent exists
        $agents = parent::get(['search' => $search]);
        if ($agents->isEmpty()) {

            // Create the agent
            return parent::store($data);

        } else {

            // Update the agent
            $record = $agents[0];
        }
        
        // Merge agents
        $merged = $this->normalizedJsonData($record->data);
        if (isset($data['name'])) $merged['name'] = $data['name'];
        if (isset($data['member'])) $merged['member'] = $data['member'];
        return parent::update($record->id, $merged);
    }
    
    /**
     * Find a data entry, given its id.
     */
    public function getOne($agent)
    {
        $res = (object)array();
        $props = get_object_vars($agent);
        foreach($props as $prop=>$val) {
            $res->$prop = array($val);
        }
        $res->objectType = 'Person';
        return $res;
    }


}
