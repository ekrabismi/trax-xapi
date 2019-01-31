<?php

namespace Trax\XapiServer;

use Trax\XapiServer\Exceptions\XapiStatementException;

class XapiStatementSchema
{
    /**
     * Exception class to be used when the schema is not valid.
     */
    public $exceptionClass = XapiStatementException::class;
    
    /**
     * Statement.
     */
    public $statement = array(
        'id' => ['format' => 'uuid'],     
        'stored' => ['format' => 'iso_date'],     
        'authority' => ['or' => [
                            'agent',
                            'authority_group',
                        ]],     
        'version' => ['format' => 'xapi_version'],     
        '_test' => ['ignore'],     
        '[extend]' => ['type' => 'statement_core'],
    );
    
    /**
     * Statement core.
     */
    public $statement_core = array(
        'actor' => ['required', 'or' => [
                            'agent',
                            'anonymous_group',
                            'identified_group',
                       ]],     
        'verb' => ['required', 'format' => 'object'],     
        'object' => ['required', 'or' => [
                            'agent',
                            'anonymous_group',
                            'identified_group',
                            'activity',
                            'statement_ref',
                            'sub_statement',
                        ]],     
        'result' => ['format' => 'object'],     
        'context' => ['format' => 'object'],     
        'timestamp' => ['format' => 'iso_date'],     
        'attachments' => ['format' => 'array', 'items' => 'object', 'type' => 'attachment'],     
    );
    
    /**
     * Agent.
     */
    public $agent = array(
        'objectType' => ['format' => 'string', 'value' => 'Agent'],
        'name' => ['format' => 'string', 'descriptive'],
        '[extend]' => ['type' => 'inverse_functional_identifier', 'required'],
    );
    
    /**
     * Anonymous Group.
     */
    public $anonymous_group = array(
        'objectType' => ['format' => 'string', 'value' => 'Group', 'required'],
        'name' => ['format' => 'string', 'descriptive'],
        'member' => ['format' => 'array', 'items' => 'object', 'type' => 'agent', 'required'],
    );
    
    /**
     * Identified Group.
     */
    public $identified_group = array(
        'objectType' => ['format' => 'string', 'value' => 'Group', 'required'],
        'name' => ['format' => 'string', 'descriptive'],
        'member' => ['format' => 'array', 'items' => 'object', 'type' => 'agent'],
        '[extend]' => ['type' => 'inverse_functional_identifier', 'required'],
    );
    
    /**
     * Authority Group.
     */
    public $authority_group = array(
        'objectType' => ['format' => 'string', 'value' => 'Group', 'required'],
        'member' => ['format' => 'array', 'items' => 'object', 'type' => 'agent', 'required'],
    );
    
    /**
     * Inverse Functional Identifier.
     */
    public $inverse_functional_identifier = array(
        '[choice]' => [
            'mbox' => ['format' => 'xapi_mbox'],
            'openid' => ['format' => 'url'],
            'mbox_sha1sum' => ['format' => 'string'],  // Dont move this. String type is more generic than previous.
            'account' => ['format' => 'object'],
        ]
    );
    
    /**
     * Account.
     */
    public $account = array(
        'homePage' => ['format' => 'url', 'required'],
        'name' => ['format' => 'string', 'required'],
    );
    
    /**
     * Verb.
     */
    public $verb = array(
        'id' => ['format' => 'url', 'required'],     
        'display' => ['format' => 'xapi_lang_map', 'descriptive'],     
    );
    
    /**
     * Activity.
     */
    public $activity = array(
        'objectType' => ['format' => 'string', 'value' => 'Activity'],
        'id' => ['format' => 'url', 'required'],     
        'definition' => ['descriptive', 'or' => [
                                'definition',
                                'interaction_definition',
                            ]],     
    );
    
    /**
     * Definition.
     */
    public $definition = array(
        'name' => ['format' => 'xapi_lang_map'],     
        'description' => ['format' => 'xapi_lang_map'],     
        'type' => ['format' => 'url'],     
        'moreInfo' => ['format' => 'url'],
        'extensions' => ['format' => 'object'],     
    );
    
    /**
     * Interaction Definition.
     */
    public $interaction_definition = array(
        'name' => ['format' => 'xapi_lang_map'],     
        'description' => ['format' => 'xapi_lang_map'],     
        'type' => ['format' => 'url', 'value' => 'http://adlnet.gov/expapi/activities/cmi.interaction'],     
        'moreInfo' => ['format' => 'url'],
        'extensions' => ['format' => 'object'],     
        '[extend]' => ['or' => [
                            'interaction_true_false',
                            'interaction_choice',
                            'interaction_fill_in',
                            'interaction_long_fill_in',
                            'interaction_matching',
                            'interaction_performance',
                            'interaction_sequencing',
                            'interaction_likert',
                            'interaction_numeric',
                            'interaction_other',
                        ]],
    );
    
    /**
     * Interaction true-false.
     */
    public $interaction_true_false = array(
        'interactionType' => ['format' => 'string', 'value' => 'true-false', 'required'],     
        'correctResponsesPattern' => ['format' => 'array', 'items' => 'string'],     
    );
    
    /**
     * Interaction choice.
     */
    public $interaction_choice = array(
        'interactionType' => ['format' => 'string', 'value' => 'choice', 'required'],     
        'correctResponsesPattern' => ['format' => 'array', 'items' => 'string'],     
        'choices' => ['format' => 'array', 'items' => 'object', 'type' => 'interaction_component'],     
    );
    
    /**
     * Interaction fill-in.
     */
    public $interaction_fill_in = array(
        'interactionType' => ['format' => 'string', 'value' => 'fill-in', 'required'],     
        'correctResponsesPattern' => ['format' => 'array', 'items' => 'string'],     
    );
    
    /**
     * Interaction long-fill-in.
     */
    public $interaction_long_fill_in = array(
        'interactionType' => ['format' => 'string', 'value' => 'long-fill-in', 'required'],     
        'correctResponsesPattern' => ['format' => 'array', 'items' => 'string'],     
    );
    
    /**
     * Interaction matching.
     */
    public $interaction_matching = array(
        'interactionType' => ['format' => 'string', 'value' => 'matching', 'required'],     
        'correctResponsesPattern' => ['format' => 'array', 'items' => 'string'],     
        'source' => ['format' => 'array', 'items' => 'object', 'type' => 'interaction_component'],     
        'target' => ['format' => 'array', 'items' => 'object', 'type' => 'interaction_component'],     
    );
    
    /**
     * Interaction performance.
     */
    public $interaction_performance = array(
        'interactionType' => ['format' => 'string', 'value' => 'performance', 'required'],     
        'correctResponsesPattern' => ['format' => 'array', 'items' => 'string'],     
        'steps' => ['format' => 'array', 'items' => 'object', 'type' => 'interaction_component'],     
    );
    
    /**
     * Interaction sequencing.
     */
    public $interaction_sequencing = array(
        'interactionType' => ['format' => 'string', 'value' => 'sequencing', 'required'],     
        'correctResponsesPattern' => ['format' => 'array', 'items' => 'string'],     
        'choices' => ['format' => 'array', 'items' => 'object', 'type' => 'interaction_component'],     
    );
    
    /**
     * Interaction likert.
     */
    public $interaction_likert = array(
        'interactionType' => ['format' => 'string', 'value' => 'likert', 'required'],     
        'correctResponsesPattern' => ['format' => 'array', 'items' => 'string'],     
        'scale' => ['format' => 'array', 'items' => 'object', 'type' => 'interaction_component'],     
    );
    
    /**
     * Interaction numeric.
     */
    public $interaction_numeric = array(
        'interactionType' => ['format' => 'string', 'value' => 'numeric', 'required'],     
        'correctResponsesPattern' => ['format' => 'array', 'items' => 'string'],     
    );
    
    /**
     * Interaction other.
     */
    public $interaction_other = array(
        'interactionType' => ['format' => 'string', 'value' => 'other', 'required'],     
        'correctResponsesPattern' => ['format' => 'array', 'items' => 'string'],     
    );
    
    /**
     * Interaction component.
     */
    public $interaction_component = array(
        'id' => ['format' => 'string', 'required'],     
        'description' => ['format' => 'xapi_lang_map'],     
    );
    
    /**
     * Statement Ref.
     */
    public $statement_ref = array(
        'objectType' => ['format' => 'string', 'value' => 'StatementRef', 'required'],
        'id' => ['format' => 'uuid', 'required'],
    );
    
    /**
     * Sub Statement.
     */
    public $sub_statement = array(
        'objectType' => ['format' => 'string', 'value' => 'SubStatement', 'required'],
        '[extend]' => ['type' => 'statement_core'],
    );
    
    /**
     * Result.
     */
    public $result = array(
        'score' => ['format' => 'object'],
        'success' => ['format' => 'boolean'],
        'completion' => ['format' => 'boolean'],
        'response' => ['format' => 'string'],
        'duration' => ['format' => 'iso_duration'],
        'extensions' => ['format' => 'object'],     
    );
    
    /**
     * Score.
     */
    public $score = array(
        'scaled' => ['format' => 'xapi_scaled'],
        'raw' => ['format' => 'numeric_strict'],
        'min' => ['format' => 'numeric_strict'],
        'max' => ['format' => 'numeric_strict'],
    );
    
    /**
     * Context.
     */
    public $context = array(
        'registration' => ['format' => 'uuid'],
        'instructor' => ['or' => [
                            'agent',
                            'anonymous_group',
                            'identified_group',
                        ]],     
        'team' => ['or' => [
                            'anonymous_group',
                            'identified_group',
                        ]],     
        'contextActivities' => ['format' => 'object', 'type' => 'context_activities'],
        'revision' => ['format' => 'string'],
        'platform' => ['format' => 'string'],
        'language' => ['format' => 'iso_lang'],
        'statement' => ['format' => 'object', 'type' => 'statement_ref'],
        'extensions' => ['format' => 'object'],     
    );
    
    /**
     * Context activities.
     */
    public $context_activities = array(
        'parent' => ['or' => [
                        'activity',
                        ['activity'],
                    ]],
        'grouping' => ['or' => [
                        'activity',
                        ['activity'],
                    ]],
        'category' => ['or' => [
                        'activity',
                        ['activity'],
                    ]],
        'other' => ['or' => [
                        'activity',
                        ['activity'],
                    ]],
    );
    
    /**
     * Attachment.
     */
    public $attachment = array(
        'usageType' => ['format' => 'url', 'required'],
        'display' => ['format' => 'xapi_lang_map', 'required'],
        'description' => ['format' => 'xapi_lang_map'],
        'contentType' => ['format' => 'content_type', 'required'],
        'length' => ['format' => 'integer_strict|min:0', 'required'],
        'sha2' => ['format' => 'string', 'required'],
        'fileUrl' => ['format' => 'url'],
    );
    
    
    /**
     * Statement.
     */
    public function statement($object)
    {
        // Voiding statement
        if (isset($object->verb) && isset($object->verb->id) && $object->verb->id == 'http://adlnet.gov/expapi/verbs/voided'
            && (!isset($object->object) || !isset($object->object->objectType) || $object->object->objectType != 'StatementRef'))
                throw new XapiStatementException('Invalid voiding "Statement": the object is not a StatementRef.');
        
        // Agent as object with missing objectType
        if (!isset($object->object->objectType)
            && (isset($object->object->mbox) || isset($object->object->openid) || isset($object->object->mbox_sha1sum) || isset($object->object->account)))
                throw new XapiStatementException('Invalid "Statement": the objectType must be "Agent".');
        
        // Context revision with anything by Activity object
        if (isset($object->context->revision) && isset($object->object->objectType) && $object->object->objectType != 'Activity')
                throw new XapiStatementException('Invalid "Statement": "context->revision" is allowed only with Activity objects.');
        
        // Context platform with anything by Activity object
        if (isset($object->context->platform) && isset($object->object->objectType) && $object->object->objectType != 'Activity')
                throw new XapiStatementException('Invalid "Statement": "context->platform" is allowed only with Activity objects.');
    }

    /**
     * SubStatement.
     */
    public function sub_statement($object)
    {
        // No nested sub-statements
        if (isset($object->object->objectType) && $object->object->objectType == 'SubStatement')
            throw new XapiStatementException('Invalid "SubStatement": nested sub-statements are not allowed.');
        
        // Context revision with anything by Activity object
        if (isset($object->context->revision) && isset($object->object->objectType) && $object->object->objectType != 'Activity')
                throw new XapiStatementException('Invalid "Statement": "context->revision" is allowed only with Activity objects.');
        
        // Context platform with anything by Activity object
        if (isset($object->context->platform) && isset($object->object->objectType) && $object->object->objectType != 'Activity')
                throw new XapiStatementException('Invalid "Statement": "context->platform" is allowed only with Activity objects.');
    }
    
    /**
     * Score.
     */
    public function score($object)
    {
        // Min
        if (isset($object->raw) && isset($object->min) && $object->raw < $object->min)
            throw new XapiStatementException('Invalid "Score": "raw" must be greater than "min".');
        
        // Max
        if (isset($object->raw) && isset($object->max) && $object->raw > $object->max)
            throw new XapiStatementException('Invalid "Score": "raw" must be lower than "max".');
    }    

    /**
     * Authority group.
     */
    public function authority_group($object)
    {
        // Wrong number of members
        if (count($object->member) != 2)
            throw new XapiStatementException('Invalid "authority" object: there must be 2 members.');
    }

    /**
     * Extensions.
     */
    public function extensions($object)
    {
        $props = get_object_vars($object);
        foreach ($props as $key => $val) {
            
            // URL
            if (traxValidate($key, 'url')) continue;

            // Must not contain spaces
            // Must contain a delimiter like ':'
            $words = explode(' ', $key);
            $parts = explode(':', $key);
            if (count($words) == 1 && count($parts) > 1) continue;

            // Error
            throw new XapiStatementException('Invalid "extension" key.');
        }
    }

}
