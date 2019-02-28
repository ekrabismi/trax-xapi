<?php

namespace Trax\XapiDesign\Builders;

class ResultBuilder
{
    use BuilderUtilities;

    /**
     * Parent statement.
     */
    protected $statement;

    /**
     * Current xAPI result.
     */
    protected $result = [];


    /**
     * Specify the parent Statement.
     */
    public function parentStatement($statement)
    {
        $this->statement = $statement;
        return $this;
    }

    /**
     * Set the score.
     */
    public function score($scaled = null, $raw = null, $min = null, $max = null)
    {
        if (!isset($scaled) && !isset($raw) && !isset($min) && !isset($max)) return $this->object();
        $this->result['score'] = [];
        if (isset($scaled)) $this->result['score']['scaled'] = $scaled;
        if (isset($raw)) $this->result['score']['raw'] = $raw;
        if (isset($min)) $this->result['score']['min'] = $min;
        if (isset($max)) $this->result['score']['max'] = $max;
        return $this->object();
    }

    /**
     * Set the success.
     */
    public function success($success = null)
    {
        if (!isset($success)) return $this->object();
        $this->result['success'] = $success;
        return $this->object();
    }

    /**
     * Set the completion.
     */
    public function completion($completion = null)
    {
        if (!isset($completion)) return $this->object();
        $this->result['completion'] = $completion;
        return $this->object();
    }

    /**
     * Set the response.
     */
    public function response($response = null)
    {
        if (!isset($response)) return $this->object();
        $this->result['response'] = $response;
        return $this->object();
    }

    /**
     * Set the duration.
     */
    public function duration($duration = null)
    {
        if (!isset($duration)) return $this->object();
        $this->result['duration'] = $duration;
        return $this->object();
    }

    /**
     * Set the extensions.
     */
    public function extensions($extensions = [])
    {
        if (empty($extensions)) return $this->object();
        $this->result['extensions'] = $extensions;
        return $this->object();
    }

    /**
     * Get the result and clear.
     */
    public function get()
    {
        $res = $this->result;
        if (isset($res['score']) && empty($res['score'])) unset($res['score']);
        $this->result = [];
        return $res;
    }

}
