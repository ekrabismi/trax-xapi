<?php

namespace Trax\XapiDesign\Tests;

use Trax\XapiDesign\Vocab\SimpleVocabIndex;

class XapiVocabExample extends SimpleVocabIndex
{
    /**
     * Verbs.
     */
    protected $verbs = [
        'verb1' => 'http://vocab.xapi.fr/verbs/verb1',
        'verb2' => 'http://vocab.xapi.fr/verbs/verb2',
    ];

    /**
     * Activity types.
     */
    protected $activityTypes = [
        'act1' => 'http://vocab.xapi.fr/activity-types/activity1',
        'act2' => 'http://vocab.xapi.fr/activity-types/activity2',
    ];

    /**
     * Attachment types.
     */
    protected $attachmentTypes = [
        'att1' => 'http://vocab.xapi.fr/attachment-types/attachment1',
        'att2' => 'http://vocab.xapi.fr/attachment-types/attachment2',
    ];
    
}
