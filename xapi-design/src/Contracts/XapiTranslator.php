<?php

namespace Trax\XapiDesign\Contracts;

interface XapiTranslator
{
    /**
     * Constructor.
     */
    public function __construct($inputData, $vocab = null, $builder = null, $levelOfDetails = 0);

    /**
     * Get the raw translation.
     */
    public function rawTranslation();

    /**
     * Get the xAPI translation.
     */
    public function xapiTranslation();

}