<?php

namespace Trax\XapiDesign\Builders;

trait BuilderUtilities
{
    /**
     * Get the verb ID.
     */
    protected function verbId($name, $source = 'adl')
    {
        if ($this->isUrl($name)) return $name;
        if (!isset($this->vocab)) throw new XapiVocabException('vocab index not defined.');
        return $this->vocab->source($source)->verb($name)->id();
    }

    /**
     * Get the activity type.
     */
    protected function activityType($name, $source = 'adl')
    {
        if ($this->isUrl($name)) return $name;
        if (!isset($this->vocab)) throw new XapiVocabException('vocab index not defined.');
        return $this->vocab->source($source)->activityType($name)->id();
    }

    /**
     * Get the attachment type.
     */
    protected function attachmentType($name, $source = 'adl')
    {
        if ($this->isUrl($name)) return $name;
        if (!isset($this->vocab)) throw new XapiVocabException('vocab index not defined.');
        return $this->vocab->source($source)->attachmentType($name)->id();
    }

    /**
     * Get lang map.
     */
    protected function langMap($name)
    {
        if (is_string($name)) {
            return array($this->lang => $name);
        } else {
            return $name;
        }
    }
    
    /**
     * Check if the value is an URL.
     */
    protected function isUrl($value)
    {
        return (is_string($value) && substr($value, 0, 4) == 'http');
    }
    
    /**
     * Check if the value is an email.
     */
    protected function isEmail($value)
    {
        return (is_string($value) && strpos($value, '@') !== false);
    }

    /**
     * Get the object to return.
     */
    protected function object()
    {
        return isset($this->statement) ? $this->statement : $this;
    }


}
