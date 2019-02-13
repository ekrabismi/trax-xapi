<?php

namespace Trax\XapiDesign\Contracts;

interface XapiVocabIndex
{
    /**
     * Focus on a vocabulary source.
     */
    public function source($name);

    /**
     * Get a verb.
     */
    public function verb($name);

    /**
     * Get an activity type.
     */
    public function activityType($name);

    /**
     * Get an attachment type.
     */
    public function attachmentType($name);

    /**
     * Get an activity extension.
     */
    public function activityExtension($name);

    /**
     * Get a result extension.
     */
    public function resultExtension($name);

    /**
     * Get a context extension.
     */
    public function contextExtension($name);

    /**
     * Get a category.
     */
    public function category($name);

}
