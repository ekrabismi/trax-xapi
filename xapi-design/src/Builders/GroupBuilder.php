<?php

namespace Trax\XapiDesign\Builders;

class GroupBuilder extends AgentBuilder
{
    use BuilderUtilities;

    /**
     * Object type.
     */
    protected $objectType = 'Group';


    /**
     * Set the member.
     */
    public function members($members = [])
    {
        if (empty($members)) return $this->object();
        $this->agent['member'] = $members;
        return $this->object();
    }

}
