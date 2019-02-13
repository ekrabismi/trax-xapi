<?php

namespace Trax\XapiDesign\Builders;

use Trax\XapiDesign\Contracts\XapiVocabIndex;

class AttachmentsBuilder
{
    use BuilderUtilities;

    /**
     * xAPI vocabulary.
     */
    protected $vocab;

    /**
     * Default lang for lang strings.
     */
    protected $lang;

    /**
     * Parent statement.
     */
    protected $statement;

    /**
     * Current xAPI attachments.
     */
    protected $attachments = [];


    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->lang = config('trax-xapi-client.xapi.lang');
    }

    /**
     * Specify the vocab index.
     */
    public function vocab(XapiVocabIndex $vocab = null)
    {
        $this->vocab = $vocab;
        return $this;
    }

    /**
     * Specify the lang for lang strings.
     */
    public function lang($lang)
    {
        $this->lang = $lang;
        return $this;
    }

    /**
     * Specify the parent Statement.
     */
    public function parentStatement($statement)
    {
        $this->statement = $statement;
        return $this;
    }

    /**
     * Add a JSON attachment.
     */
    public function json($usageType, $display, $contentType, $length, $sha2, $description = null, $fileUrl = null)
    {
        $attachment = array(
            'usageType' => $this->attachmentType($usageType),
            'display' => $this->langMap($display),
            'contentType' => $contentType,
            'length' => $length,
            'sha2' => $sha2,
        );
        if (isset($description)) $attachment['description'] = $this->langMap($description);
        if (isset($fileUrl)) $attachment['fileUrl'] = $fileUrl;
        $this->attachments[] = $attachment;
        return $this->object();
    }


    /**
     * Add a raw attachment.
     */
    public function raw($rawAttachment, $usageType, $display, $description = null)
    {
        if (is_array($rawAttachment)) $rawAttachment = (object)$rawAttachment;
        $attachment = array(
            'usageType' => $this->attachmentType($usageType),
            'display' => $this->langMap($display),
            'contentType' => $rawAttachment->contentType,
            'length' => mb_strlen($rawAttachment->content, '8bit'),
            'sha2' => hash('sha256', $rawAttachment->content),
        );
        if (isset($description)) $attachment['description'] = $this->langMap($description);
        $this->attachments[] = $attachment;
        return $this->object();
    }

    /**
     * Get the attachments and clear.
     */
    public function get()
    {
        $res = $this->attachments;
        $this->attachments = [];
        return $res;
    }

}
