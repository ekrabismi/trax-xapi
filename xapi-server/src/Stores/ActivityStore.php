<?php

namespace Trax\XapiServer\Stores;

use Trax\DataStore\Stores\DataStoreFilter;

traxCreateDataStoreSwitchClass('Trax\XapiServer\Stores', 'trax-xapi-server', 'Activity');

class ActivityStore extends ActivityStoreSwitch
{
    use DataStoreFilter;

    /**
     * The attributes that should never be changed.
     */
    protected $protected = ['created_at', 'updated_at'];

    /**
     * Props used for the global search.
     */
    protected $globalSearchScopes = array('data.definition.name');
    

    /**
     * Get data entries.
     */
    public function get($args = array(), $options = array())
    {
        if (isset($args['filters'])) {

            // Create search arg
            if (!isset($args['search'])) $args['search'] = [];

            // Filters
            $this->addLikeFilterCondition($args, 'id', 'data.id');
            $this->addLikeFilterCondition($args, 'name', 'data.definition.name');
            $this->addLikeFilterCondition($args, 'description', 'data.definition.description');
            $this->addLikeFilterCondition($args, 'type', 'data.definition.type');

            // Free filters arg
            unset($args['filters']);
        }
        return parent::get($args, $options);
    }

    
}
