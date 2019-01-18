<?php

namespace Trax\XapiServer\Stores;

trait XapiStore 
{

    /**
     * Get normalized date for requests.
     */
    protected function normalizedDate($isoDateString)
    {
        return traxIsoTimestamp($isoDateString);
    }
    
    /**
     * Get the search fields of an Agent.
     */
    protected function getAgentSearch($agent, $target)
    {
        $search = array();
        if (isset($agent['mbox'])) $search[$target.'.mbox'] = $agent['mbox'];
        else if (isset($agent['mbox_sha1sum'])) $search[$target.'.mbox_sha1sum'] = $agent['mbox_sha1sum'];
        else if (isset($agent['openid'])) $search[$target.'.openid'] = $agent['openid'];
        else if (isset($agent['account'])) {
            $search[$target.'.account.homePage'] = $agent['account']['homePage'];
            $search[$target.'.account.name'] = $agent['account']['name'];
        }
        return $search;
    }


}
