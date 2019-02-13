<?php

namespace Trax\XapiDesign\Builders;

use Trax\XapiDesign\Contracts\XapiVocabIndex;

class VerbBuilder
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
     * Current xAPI verb.
     */
    protected $verb = [];


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
     * Set the id.
     */
    public function id($name, $source = 'adl')
    {
        $this->verb['id'] = $this->verbId($name, $source);
        return $this->object();
    }

    /**
     * Set the display.
     */
    public function display($display = null)
    {
        if (!isset($display)) return $this->object();
        $this->verb['display'] = $this->langMap($display);
        return $this->object();
    }

    /**
     * Get the agent and clear.
     */
    public function get()
    {
        $res = $this->verb;
        $this->verb = [];
        return $res;
    }

}
