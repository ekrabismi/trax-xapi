<?php

namespace Trax\XapiDesign\Translators;

abstract class ContextTranslator extends XapiTranslator
{
    /**
     * Get the xAPI translation.
     */
    public function xapiTranslation()
    {
        if (!isset($this->translation)) $this->translate();
        $builder = $this->builder->contextBuilder();
        $this->addBuilderTranslationProps($builder, ['registration', 'instructor', 'team', 'revision', 'platform', 'language', 'statement', 'extensions']);
        $builder->contextActivities($this->contextActivities());
        return $builder->get();
    }

    /**
     * Get a category activity.
     */
    protected function contextActivities()
    {
        $contextActivities = [];
        if (isset($this->translation->categoryActivities) && !empty($this->translation->categoryActivities)) {
            $contextActivities['category'] = $this->translation->categoryActivities;
        }
        if (isset($this->translation->parentActivities) && !empty($this->translation->parentActivities)) {
            $contextActivities['parent'] = $this->translation->parentActivities;
        }
        if (isset($this->translation->groupingActivities) && !empty($this->translation->groupingActivities)) {
            $contextActivities['grouping'] = $this->translation->groupingActivities;
        }
        if (isset($this->translation->otherActivities) && !empty($this->translation->otherActivities)) {
            $contextActivities['other'] = $this->translation->otherActivities;
        }
        return $contextActivities;
    }

    /**
     * Get a category activity.
     */
    protected function categoryActivity($type, $category)
    {
        return $this->builder->activityBuilder()
            ->id($this->vocab->category($category)->id())
            ->type($type)
            ->get();
    }


}