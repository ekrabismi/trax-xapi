<?php

namespace Trax\XapiServer;

class XapiStatementFormatter extends XapiStatementValidator
{
    
    /**
     * Format a statement.
     */
    public function format($statement, $format, $lang = null)
    {
        // Exact format
        if ($format == 'exact') return $this->formatExact($statement);
        
        // IDs format
        if ($format == 'ids') return $this->formatIds($statement);
        
        // Canonical format
        if ($format == 'canonical') return $this->formatCanonical($statement, $lang);
        
        return false;
    }
    
    /**
     * Return statement with the 'exact' format.
     */
    protected function formatExact($statement)
    {
        return $statement;
    }
    
    /**
     * Return statement with the 'ids' format.
     */
    protected function formatIds($statement)
    {
        $this->setTransformer(function($object, $prop, $schema) {
            
            // Remove all descriptive props
            if (in_array('descriptive', $schema)) unset($object->$prop);
            
            // objectType = 'Activity' as it is always optional
            if ($prop == 'objectType' && $object->$prop == 'Activity') unset($object->$prop);
            
        });
        $this->validate($statement);
        return $statement;
    }
    
    /**
     * Return statement with the 'canonical' format.
     */
    protected function formatCanonical($statement, $lang = null)
    {
        $this->setTransformer(function($object, $prop, $schema) use ($lang) {
            
            // Canonize lang maps
            if (isset($schema['format']) && $schema['format'] == 'xapi_lang_map') $object->$prop = $this->canonize($object->$prop, $lang);
        });
        $this->validate($statement);
        return $statement;
    }

    /**
     * Canonize a lang string.
     */
    protected function canonize($langMap, $headerLang = null) {
        $preferedLang = isset($headerLang) ? $headerLang : 'en';
        $langs = get_object_vars($langMap);
        
        // Search from exact prefered lang
        foreach($langs as $lang => $label) {
            if (substr($lang, 0, strlen($preferedLang)) == $preferedLang) {
                return (object)array($lang => $label);
            }
        }

        // Search from global prefered lang
        $parts = explode('-', $preferedLang);
        if (count($parts) == 2) {
            $preferedLang = $parts[0];
            foreach($langs as $lang => $label) {
                if (substr($lang, 0, strlen($preferedLang)) == $preferedLang) {
                    return (object)array($lang => $label);
                }
            }
        }

        // First lang
        foreach($langs as $lang => $label) {
            return (object)array($lang => $label);
        }
        
        // No lang
        return $langMap;
    }


}
