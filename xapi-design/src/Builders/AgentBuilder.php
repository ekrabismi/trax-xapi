<?php

namespace Trax\XapiDesign\Builders;

class AgentBuilder
{
    use BuilderUtilities;

    /**
     * Parent statement.
     */
    protected $statement;

    /**
     * Current xAPI agent.
     */
    protected $agent = [];

    /**
     * Object type.
     */
    protected $objectType = 'Agent';


    /**
     * Specify the parent Statement.
     */
    public function parentStatement($statement)
    {
        $this->statement = $statement;
        return $this;
    }

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->agent['objectType'] = $this->objectType;
    }

    /**
     * Set the account.
     */
    public function account($homePage, $name)
    {
        $this->agent['account'] = [
            'homePage' => $homePage,
            'name' => $name,
        ];
        return $this->object();
    }

    /**
     * Set the mbox.
     */
    public function mbox($mbox)
    {
        $this->agent['mbox'] = 'mailto:' . $mbox;
        return $this->object();
    }

    /**
     * Set the mbox_sha1sum.
     */
    public function mboxSha1sum($mbox)
    {
        $this->agent['mbox_sha1sum'] = $mbox;
        return $this->object();
    }

    /**
     * Set the openid.
     */
    public function openid($openid)
    {
        $this->agent['openid'] = $openid;
        return $this->object();
    }

    /**
     * Set the name.
     */
    public function name($name = null)
    {
        if (!isset($name)) return $this->object();
        $this->agent['name'] = $name;
        return $this->object();
    }

    /**
     * Get the agent and clear.
     */
    public function get()
    {
        $res = $this->agent;
        $this->agent = [];
        return $res;
    }

}
