<?php

namespace Trax\XapiServer;

class XapiStatementValidator extends SchemaParser
{
    
    /**
     * Construct.
     */
    public function __construct()
    {
        parent::__construct(new XapiStatementSchema());
    }
    
    /**
     * Validate a Statement.
     */
    public function validate($statement)
    {
        $this->parseObject($statement, 'statement');
    }
    
    /**
     * Validate an Agent (or the Agent param of GET Statement API).
     */
    public function validateAgent($actor, $orIdentifiedGroup = true)
    {
        if ($orIdentifiedGroup) $this->parseOr($actor, 'actor', ['agent', 'identified_group']);
        else $this->parseObject($actor, 'agent');
    }
    
}
