<?php

namespace Trax\XapiDesign\Translators;

abstract class ActivityTranslator extends XapiTranslator
{
    /**
     * Get the xAPI translation.
     */
    public function xapiTranslation()
    {
        if (!isset($this->translation)) $this->translate();
        $builder = $this->builder->activityBuilder();
        $this->addBuilderTranslationProps($builder, ['id', 'type', 'name', 'description', 'moreInfo', 'extensions']);
        return $builder->get();
    }

}