<?php

namespace Trax\XapiServer\Stores;

use Trax\DataStore\Stores\DataStoreFilter;

traxCreateDataStoreSwitchClass('Trax\XapiServer\Stores', 'trax-xapi-server', 'Agent');

class AgentStore extends AgentStoreSwitch
{
    use DataStoreFilter;
    
    /**
     * Props used for the global search.
     */
    protected $globalSearchScopes = array('data.name', 'data.mbox', 'data.openid', 'data.account.homePage', 'data.account.name');


    /**
     * Get data entries.
     */
    public function get($args = array(), $options = array())
    {
        if (isset($args['filters'])) {

            // Create search arg
            if (!isset($args['search'])) $args['search'] = [];

            // Conditional filters based on Agent type
            if (isset($args['filters']['idType']) && !empty($args['filters']['idType'])) {
                $prop = 'data.'.$args['filters']['idType'];

                // Type
                $this->addExistsFilterCondition($args, 'idType', $prop);

                // Mbox
                if ($prop == 'data.mbox') {
                    $this->addLikeFilterCondition($args, 'mbox', 'data.mbox');
                }

                // OpenID
                if ($prop == 'data.openid') {
                    $this->addLikeFilterCondition($args, 'openid', 'data.openid');
                }

                // Account
                if ($prop == 'data.account') {
                    $this->addLikeFilterCondition($args, 'account_homepage', 'data.account.homePage');
                    $this->addLikeFilterCondition($args, 'account_name', 'data.account.name');
                }
            } 

            // Other filters
            $this->addEqualFilterCondition($args, 'objectType', 'data.objectType', ['default' => 'Agent']);
            $this->addLikeFilterCondition($args, 'name', 'data.name');

            // Free filters arg
            unset($args['filters']);
        }
        return parent::get($args, $options);
    }

}
