<?php

namespace Trax\XapiServer;

class SchemaParser
{
    /**
     * Schema
     */
    protected $schema;
    
    /**
     * Transformer
     */
    protected $transformer;
    
    
    /**
     * Construct.
     */
    public function __construct($schema)
    {
        $this->schema = $schema;
    }
    
    /**
     * Set transformation function during parsing.
     */
    protected function setTransformer($handler)
    {
        $this->transformer = $handler;
    }

    /**
     * Validate an object compared to several potential schemas.
     */
    protected function parseOr($object, $schemaProp, $schemaPropList)
    {
        foreach($schemaPropList as $altSchema) {
            try {
                $this->parseObject($object, $altSchema);
                return;
            } catch(\Exception $e) {
            }
        }
        $this->exception('None of the following schemas were found '.json_encode($schemaPropList).' in "'.$schemaProp.'".');
    }

    /**
     * Validate an object compared to a schema.
     */
    protected function parseObject($object, $schemaProp, $schemaProps = null)
    {
        // Empty arrays resulting from empty objects recording
        if (is_array($object) && empty($object)) $object = (object)$object;
        
        // Check object
        if (!is_object($object))
            $this->exception('"'.$schemaProp.'" must be an object.');

        // Prepare object
        $objectProps = get_object_vars($object);

        // Prepare schema
        if (!isset($schemaProps)) {
            
            // Validate with schema
            $schemaProps = $this->objectSchema($object, $schemaProp);
            
            // Validate with schema method
            if (method_exists($this->schema, $schemaProp)) $this->schema->$schemaProp($object);
            
            // Validate with local method
            if (method_exists($this, $schemaProp)) $this->$schemaProp($object);
            
            // Validate only with method
            if (!$schemaProps) return true;
        }
        
        // Check that all required props are provided by the object
        foreach($schemaProps as $prop => $val) {
            if (in_array('required', $val) && !isset($objectProps[$prop])) {
                $this->exception('Missing required prop "'.$prop.'" in "'.$schemaProp.'".');
            }
        }
        
        // Check all object props
        foreach($objectProps as $prop => $val) {
            
            // Check that all object props are in the schema
            if (!isset($schemaProps[$prop])) {
                $this->exception('Unknown prop "'.$prop.'" in "'.$schemaProp.'".');
            }
            $propSchema = $schemaProps[$prop];
            
            // Ignore some props
            if (in_array('ignore', $propSchema)) continue;

            // Resolve 'or' case
            if (isset($propSchema['or'])) {
                $found = false;
                foreach($propSchema['or'] as $orSchema) {
                    if (is_string($orSchema)) {
                        
                        // Validate an Object
                        try {
                            $found = $this->parseObject($val, $orSchema);
                            break;
                        } catch (\Exception $e) {
                        }
                        
                    } else {

                        // Validate a list of Objects
                        try {
                            $found = $this->parseArray($val, $schemaProp.'->'.$prop, 'object', $orSchema[0]);
                            break;
                        } catch (\Exception $e) {
                        }
                    }
                }
                if (!$found) {
                    $this->exception('None of the following schemas were found '.json_encode($propSchema['or']).' in "'.$schemaProp.'->'.$prop.'".');
                }
                
            // Resolve 'format' case
            
            } else if ($propSchema['format'] == 'object') {

                // Resolve Object prop
                $type = isset($propSchema['type']) ? $propSchema['type'] : $prop;
                $this->parseObject($val, $type);
            
            } else if ($propSchema['format'] == 'array') {

                // Resolve Array prop
                $type = isset($propSchema['type']) ? $propSchema['type'] : null;
                $this->parseArray($val, $schemaProp.'->'.$prop, $propSchema['items'], $type);
            
            } else {
                
                // Resolve simple prop
                $fixedValue = isset($propSchema['value']) ? $propSchema['value'] : null;
                $this->parseValue($val, $schemaProp.'->'.$prop, $propSchema['format'], $fixedValue);
            }
            
            // Apply val transformation to prop
            $object->$prop = $val;

            // Apply object tranformer
            if ($this->transformer) {
                $handler = $this->transformer;
                $handler($object, $prop, $propSchema);
            }
        }
        return true;
    }

    /**
     * Validate an Array compared to a schema.
     */
    protected function parseArray($array, $schemaProp, $items, $type = null)
    {
        // Check array
        if (!is_array($array))
            $this->exception('"'.$schemaProp.'" must be an array.');

        foreach($array as $item) {
            if ($items == 'object') {
                
                // Validate object
                $this->parseObject($item, $type);
                
            } else {
                
                // Validate simple value
                $this->parseValue($item, $schemaProp, $items);
            }
        }
        return true;
    }
    
    /**
     * Validate a value compared to rules.
     */
    protected function parseValue($value, $schemaProp, $rules, $fixedValue = null)
    {
        // Check fixed value
        if (isset($fixedValue) && $value !== $fixedValue) 
            $this->exception('"'.$schemaProp.'" value must be "'.$fixedValue.'".');
        
        // Check rules
        if (!traxValidate($value, $rules))
            $this->exception('"'.$schemaProp.'" do not match with "'.$rules.'" rules.');
        
        return true;
    }
    
    /**
     * Return a schema, after resolving schema extensions.
     */
    protected function objectSchema($object, $schemaProp)
    {
        // No schema
        if (!isset($this->schema->$schemaProp)) return false;
        
        // No extend found
        $schemaProps = $this->schema->$schemaProp;
        if (!isset($schemaProps['[extend]'])) return $schemaProps;
        
        // Extend found
        $extend = $schemaProps['[extend]'];
        unset($schemaProps['[extend]']);
        
        // 'Or' extend
        if (isset($extend['or'])) {
            foreach($extend['or'] as $schemaItem) {

                // Merge schemas
                $schemaItemProps = array_merge($this->schema->$schemaItem, $schemaProps);
                
                // Try to validate
                try {
                    $this->parseObject($object, $schemaItem, $schemaItemProps);
                    return $schemaItemProps;
                } catch (\Exception $e) {
                }
            }
            $this->exception('No matching schema found for [extend]=>or in "'.$schemaProp.'".');
        }

        // 'Type' extend
        $childName = $extend['type'];
        $childSchema = $this->schema->$childName;
        
        // Child without a [choice] prop
        if (!isset($childSchema['[choice]'])) {
            return array_merge($childSchema, $schemaProps);
        }
        
        // Child with a [choice] prop. One of them is required.
        $choices = $childSchema['[choice]'];
        unset($childSchema['[choice]']);
        foreach($choices as $prop => $schemaItem) {
            
            // Merge schemas
            $mergedSchema = array_merge($childSchema, $schemaProps);
            $mergedSchema[$prop] = $schemaItem;
            if (in_array('required', $extend)) $mergedSchema[$prop][] = 'required';
            
            // Try to validate
            try {
                $this->parseObject($object, $childName, $mergedSchema);
                return $mergedSchema;
            } catch (\Exception $e) {
            }
        }
        $this->exception('No matching schema found for [choice] in "'.$schemaProp.'".');
    }

    /**
     * Throw exception.
     */
    protected function exception($message)
    {
        throw new $this->schema->exceptionClass($message);
    }
    
}
