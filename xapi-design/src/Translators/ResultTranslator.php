<?php

namespace Trax\XapiDesign\Translators;

abstract class ResultTranslator extends XapiTranslator
{
    /**
     * Get the xAPI translation.
     */
    public function xapiTranslation()
    {
        if (!isset($this->translation)) $this->translate();
        $builder = $this->builder->resultBuilder();
        $this->addBuilderTranslationProps($builder, ['success', 'score', 'completion', 'response', 'duration', 'extensions']);
        return $builder->get();
    }

}