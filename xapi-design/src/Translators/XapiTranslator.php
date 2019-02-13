<?php

namespace Trax\XapiDesign\Translators;

use Trax\XapiDesign\Contracts\XapiTranslator as Contract;

abstract class XapiTranslator implements Contract
{
    /**
     * Input data.
     */
    protected $inputData;

    /**
     * Vocabulary.
     */
    protected $vocab;

    /**
     * Statement builder.
     */
    protected $builder;

    /**
     * Level of details 
     * - 0 = minimum : get only the ID to refer Agent API items.
     * - 1 = intermediate : get the actor ID, and include group members when applicable.
     * - 2 = full : get all the actor information, including its name.
     */
    protected $levelOfDetails;

    /**
     * Translation.
     */
    protected $translation;


    /**
     * Constructor.
     */
    public function __construct($inputData, $vocab = null, $builder = null, $levelOfDetails = 0)
    {
        $this->inputData = $inputData;
        $this->levelOfDetails = $levelOfDetails;
        $this->vocab = $vocab;
        $this->builder = $builder;
    }

    /**
     * Perform the translation.
     */
    protected function translate()
    {
    }

    /**
     * Get the raw translation.
     */
    public function rawTranslation()
    {
        if (!isset($this->translation)) $this->translate();
        return $this->translation;
    }

    /**
     * Add translation props to a given builder.
     */
    protected function addBuilderTranslationProps(&$builder, $props)
    {
        foreach($props as $prop) {
            if (isset($this->translation->$prop)) $builder->$prop($this->translation->$prop);
        }
    }

}