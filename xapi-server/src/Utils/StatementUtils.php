<?php

namespace Trax\XapiServer\Utils;

trait StatementUtils
{

    /**
     * Get Activities.
     */
    protected function statementsActivities($statements)
    {
        // Statements batch
        $activities = array();
        if (is_array($statements)) {
            foreach($statements as $statement) {
                $activities = array_merge($activities, $this->statementsActivities($statement));
            }
            return $activities;
        }
        
        // Single statement
        $statement = $statements;
        
        // Activity from the object
        if (!isset($statement->object->objectType) || $statement->object->objectType == 'Activity')
            $activities[] = $statement->object;
            
        // Activity from substatement
        if (isset($statement->object->objectType) && $statement->object->objectType == 'SubStatement') {
            $activities = array_merge($activities, $this->statementsActivities($statement->object));
        }
        
        // Result
        return $activities;
    }
    
    /**
     * Get Agents.
     */
    protected function statementsAgents($statements)
    {
        // Statements batch
        $agents = array();
        if (is_array($statements)) {
            foreach($statements as $statement) {
                $agents = array_merge($agents, $this->statementsAgents($statement));
            }
            return $agents;
        }
        
        // Single statement
        $statement = $statements;
        
        // Agents from the actor
        $agents[] = $statement->actor;
            
        // Agents from the object
        if (isset($statement->object->objectType) && ($statement->object->objectType == 'Agent' || $statement->object->objectType == 'Group')) {
            $agents[] = $statement->object;
        }
            
        // Agents from substatement
        if (isset($statement->object->objectType) && $statement->object->objectType == 'SubStatement') {
            $agents = array_merge($agents, $this->statementsAgents($statement->object));
        }
        
        // Result
        return $agents;
    }
        
}
