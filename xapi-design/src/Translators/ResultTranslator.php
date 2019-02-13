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
        $this->addBuilderTranslationProps($builder, ['success', 'completion', 'response', 'duration', 'extensions']);
        $builder->score()
            ->raw($this->translation->scoreRaw)
            ->scaled($this->translation->scoreScaled)
            ->min($this->translation->scoreMin)
            ->max($this->translation->scoreMax);
        return $builder->get();
    }

}