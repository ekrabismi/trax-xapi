<?php

namespace Trax\XapiDesign\Builders;

use Trax\XapiDesign\Contracts\XapiVocabIndex;

class ActivityBuilder
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
     * ID prefix.
     */
    protected $prefix = 'http://xapi.fr/activities/';

    /**
     * Parent statement.
     */
    protected $statement;

    /**
     * Current xAPI activity.
     */
    protected $activity = [];

    /**
     * Interaction.
     */
    protected $interaction = false;


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
     * Specify the activity ID prefix.
     */
    public function prefix($prefix)
    {
        $this->prefix = $prefix;
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
     * Set the ID.
     */
    public function id($id)
    {
        $this->activity['objectType'] = 'Activity';
        $this->activity['id'] = $this->isUrl($id) ? $id : $this->prefix . $id;
        return $this->object();
    }

    /**
     * Define this activity as an interaction.
     */
    public function interaction()
    {
        $this->interaction = true;
        if (!isset($this->activity['definition'])) $this->activity['definition'] = [];
        $this->activity['definition']['type'] = $this->activityType('cmi.interaction');
        return $this->object();
    }

    /**
     * Set the type.
     */
    public function type($type = null, $source = null)
    {
        if (!isset($type)) return $this->object();
        if (!isset($this->activity['definition'])) $this->activity['definition'] = [];
        if ($this->interaction) {
            $this->activity['definition']['interactionType'] = $type;
        } else {
            $this->activity['definition']['type'] = $this->activityType($type, $source);
        }
        return $this->object();
    }

    /**
     * Set the name.
     */
    public function name($name = null)
    {
        if (!isset($name)) return $this->object();
        if (!isset($this->activity['definition'])) $this->activity['definition'] = [];
        $this->activity['definition']['name'] = $this->langMap($name);
        return $this->object();
    }

    /**
     * Set the description.
     */
    public function description($description = null)
    {
        if (!isset($description)) return $this->object();
        if (!isset($this->activity['definition'])) $this->activity['definition'] = [];
        $this->activity['definition']['description'] = $this->langMap($description);
        return $this->object();
    }

    /**
     * Set the moreInfo.
     */
    public function moreInfo($moreInfo = null)
    {
        if (!isset($moreInfo)) return $this->object();
        if (!isset($this->activity['definition'])) $this->activity['definition'] = [];
        $this->activity['definition']['moreInfo'] = $moreInfo;
        return $this->object();
    }

    /**
     * Set the extensions.
     */
    public function extensions($extensions = [])
    {
        if (empty($extensions)) return $this->object();
        if (!isset($this->activity['definition'])) $this->activity['definition'] = [];
        $this->activity['definition']['extensions'] = $extensions;
        return $this->object();
    }

    /**
     * Set the correctResponsesPattern.
     */
    public function pattern($pattern)
    {
        if (!$this->interaction) return $this->object();
        $this->activity['definition']['correctResponsesPattern'] = $data;
        return $this->object();
    }

    /**
     * Set the choices.
     */
    public function choices($data)
    {
        if (!$this->interaction) return $this->object();
        $this->activity['definition']['choices'] = $data;
        return $this->object();
    }

    /**
     * Set the source.
     */
    public function source($data)
    {
        if (!$this->interaction) return $this->object();
        $this->activity['definition']['source'] = $data;
        return $this->object();
    }

    /**
     * Set the target.
     */
    public function target($data)
    {
        if (!$this->interaction) return $this->object();
        $this->activity['definition']['target'] = $data;
        return $this->object();
    }

    /**
     * Set the steps.
     */
    public function steps($data)
    {
        if (!$this->interaction) return $this->object();
        $this->activity['definition']['steps'] = $data;
        return $this->object();
    }

    /**
     * Set the scale.
     */
    public function scale($data)
    {
        if (!$this->interaction) return $this->object();
        $this->activity['definition']['scale'] = $data;
        return $this->object();
    }

    /**
     * Get the activity and clear.
     */
    public function get()
    {
        $res = $this->activity;
        $this->activity = [];
        $this->interaction = false;
        return $res;
    }

}
