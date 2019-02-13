<?php

namespace Trax\XapiDesign\Vocab;

use Trax\XapiDesign\Exceptions\XapiVocabException;
use Trax\XapiDesign\Contracts\XapiVocabIndex;
use Trax\XapiDesign\Contracts\XapiVocabItem;

class SimpleVocabIndex implements XapiVocabIndex, XapiVocabItem
{
    /**
     * Verbs.
     */
    protected $verbs = [
        /*
        'name1' => 'http://iri1',
        'name2' => 'http://iri2',
        */
    ];

    /**
     * Activity types.
     */
    protected $activityTypes = [
        /*
        'name1' => 'http://iri1',
        'name2' => 'http://iri2',
     */
    ];

    /**
     * Attachment types.
     */
    protected $attachmentTypes = [
        /*
        'name1' => 'http://iri1',
        'name2' => 'http://iri2',
     */
    ];

    /**
     * Activity extensions.
     */
    protected $activityExtensions = [
        /*
        'name1' => 'http://iri1',
        'name2' => 'http://iri2',
     */
    ];

    /**
     * Context extensions.
     */
    protected $contextExtensions = [
        /*
        'name1' => 'http://iri1',
        'name2' => 'http://iri2',
     */
    ];

    /**
     * Result extensions.
     */
    protected $resultExtensions = [
        /*
        'name1' => 'http://iri1',
        'name2' => 'http://iri2',
     */
    ];

    /**
     * Categories.
     */
    protected $categories = [
        /*
        'name1' => 'http://iri1',
        'name2' => 'http://iri2',
     */
    ];

    /**
     * Current item.
     */
    protected $current;

    
    /**
     * Focus on a vocabulary source.
     */
    public function source($name) {
        return $this;
    }

    /**
     * Get a verb.
     */
    public function verb($name)
    {
        if (!isset($this->verbs[$name])) throw new XapiVocabException("unknown verb '". $name ."'");
        $this->current = $this->verbs[$name];
        return $this;
    }

    /**
     * Get an activity type.
     */
    public function activityType($name)
    {
        if (!isset($this->activityTypes[$name])) throw new XapiVocabException("unknown activity type '" . $name . "'");
        $this->current = $this->activityTypes[$name];
        return $this;
    }


    /**
     * Get an attachment type.
     */
    public function attachmentType($name)
    {
        if (!isset($this->attachmentTypes[$name])) throw new XapiVocabException("unknown attachment type '" . $name . "'");
        $this->current = $this->attachmentTypes[$name];
        return $this;
    }

    /**
     * Get an activity extension.
     */
    public function activityExtension($name)
    {
        if (!isset($this->activityExtensions[$name])) throw new XapiVocabException("unknown activity extension '" . $name . "'");
        $this->current = $this->activityExtensions[$name];
        return $this;
    }

    /**
     * Get a result extension.
     */
    public function resultExtension($name)
    {
        if (!isset($this->resultExtensions[$name])) throw new XapiVocabException("unknown result extension '" . $name . "'");
        $this->current = $this->resultExtensions[$name];
        return $this;
    }

    /**
     * Get a context extension.
     */
    public function contextExtension($name)
    {
        if (!isset($this->contextExtensions[$name])) throw new XapiVocabException("unknown context extension '" . $name . "'");
        $this->current = $this->contextExtensions[$name];
        return $this;
    }

    /**
     * Get a category.
     */
    public function category($name)
    {
        if (!isset($this->categories[$name])) throw new XapiVocabException("unknown category '" . $name . "'");
        $this->current = $this->categories[$name];
        return $this;
    }

    /**
     * Get the id (IRI).
     */
    public function id()
    {
        return $this->current;
    }

}
