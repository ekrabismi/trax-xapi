<?php

namespace Trax\XapiServer;

class XapiStatementComparator
{
    /**
     * Compare 2 statements.
     */
    protected $ignore = [
        'id',
        'authority',
        'stored',
        'timestamp',
        'version',
        'definition',
        'display',
        'member',
        'attachments',
    ];
    
    
    /**
     * Compare 2 statements.
     */
    public function compare($statement1, $statement2)
    {
        return $this->compareObjects($statement1, $statement2);
    }
    
    /**
     * Compare 2 items, without knowing their type.
     */
    protected function compareItems($item1, $item2)
    {
         if (is_object($item1) && is_object($item2)) return $this->compareObjects($item1, $item2);
         if (is_array($item1) && is_array($item2)) return $this->compareArrays($item1, $item2);
         return ($item1 === $item2);
    }
    
    /**
     * Compare 2 objects.
     */
    protected function compareObjects($item1, $item2)
    {
        // From record
        $props = get_object_vars($item1);
        foreach($props as $key => $val) {
            
            // Ignore some props
            if (in_array($key, $this->ignore)) continue;
            
            // Missing prop in $item2
            if (!isset($item2->$key)) return false;
        }
        
        // From source
        $props = get_object_vars($item2);
        foreach($props as $key => $val) {
             
            // Ignore some props
            if (in_array($key, $this->ignore)) continue;
            
            // Missing prop in $item1
            if (!isset($item1->$key)) return false;
            
            // Compare values
            $same = $this->compareItems($item1->$key, $val);
            if (!$same) return false;
        }
        return true;
    }
    
    /**
     * Compare 2 arrays.
     */
    protected function compareArrays($item1, $item2)
    {
        // Same size
        if (count($item1) != count($item2)) return false;
        
        // Same order
        asort($item2);
        asort($item1);
        
        // Same values 
        foreach($item2 as $key => $val) {
             $same = $this->compareItems($item1[$key], $val);
             if (!$same) return false;
        }
        return true;
    }
    
    
}

