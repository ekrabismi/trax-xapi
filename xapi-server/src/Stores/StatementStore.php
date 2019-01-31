<?php

namespace Trax\XapiServer\Stores;

use Trax\DataStore\Stores\DataStoreFilter;

traxCreateDataStoreSwitchClass('Trax\XapiServer\Stores', 'trax-xapi-server', 'Statement');

class StatementStore extends StatementStoreSwitch
{
    use DataStoreFilter;

    /**
     * The attributes that should be visible.
     */
    protected $visible = ['id', 'voided', 'data', 'created_at', 'updated_at'];

    /**
     * The attributes that should never be changed.
     */
    protected $protected = ['created_at', 'updated_at'];

    /**
     * Columns.
     */
    protected $columns = ['voided'];
    
    /**
     * Default ordering settings.
     */
    protected $defaultOrderingCol = 'id';
    protected $defaultOrderingDir = 'desc';
    
    
    /**
     * Get data entries.
     */
    public function get($args = array(), $options = array())
    {
        if (isset($args['filters'])) {

            // Create search arg
            if (!isset($args['search'])) $args['search'] = [];

            // Actor filters
            $this->addEqualFilterCondition($args, 'actor.objectType', 'data.actor.objectType', ['default' => 'Agent']);
            if ($this->getBoolFilterValue($args, 'actor.include_object')) {
                $this->addEqualFilterCondition($args, 'actor.mbox', ['data.actor.mbox', 'data.object.mbox'], ['prefix' => 'mailto:']);
                $this->addEqualFilterCondition($args, 'actor.openid', ['data.actor.openid', 'data.object.openid']);
                $this->addEqualFilterCondition($args, 'actor.account_homepage', ['data.actor.account.homePage', 'data.object.account.homePage']);
                $this->addEqualFilterCondition($args, 'actor.account_name', ['data.actor.account.name', 'data.object.account.name']);
            } else {
                $this->addEqualFilterCondition($args, 'actor.mbox', 'data.actor.mbox', ['prefix' => 'mailto:']);
                $this->addEqualFilterCondition($args, 'actor.openid', 'data.actor.openid');
                $this->addEqualFilterCondition($args, 'actor.account_homepage', 'data.actor.account.homePage');
                $this->addEqualFilterCondition($args, 'actor.account_name', 'data.actor.account.name');
            }

            // Verb filters
            $this->addEqualFilterCondition($args, 'verb.id', 'data.verb.id');

            // Activity filters
            $objectType = $this->addEqualFilterCondition($args, 'object.objectType', 'data.object.objectType', ['default' => 'Activity']);
            if ($objectType == 'Activity') {
                if ($this->getBoolFilterValue($args, 'object.include_context')) {
                    $this->addEqualFilterCondition($args, 'object.id', [
                        'data.object.id', 
                        'data.context.contextActivities.parent[*].id',
                        'data.context.contextActivities.grouping[*].id',
                        'data.context.contextActivities.category[*].id',
                        'data.context.contextActivities.other[*].id'
                    ]);
                } else {
                    $this->addEqualFilterCondition($args, 'object.id', 'data.object.id');
                    $this->addEqualFilterCondition($args, 'object.type', 'data.object.definition.type');
                }

            } else if ($objectType == 'Agent' || $objectType == 'Group') {
                
                $this->addEqualFilterCondition($args, 'object.mbox', 'data.object.mbox', ['prefix' => 'mailto:']);
                $this->addEqualFilterCondition($args, 'object.openid', 'data.object.openid');
                $this->addEqualFilterCondition($args, 'object.account_homepage', 'data.object.account.homePage');
                $this->addEqualFilterCondition($args, 'object.account_name', 'data.object.account.name');
            }

            // Completion
            if ($completion = $this->getFilterValue($args, 'result.completion')) {
                if ($completion == 'completed') {
                    $this->addBoolFilterCondition($args, 'result.completion', 'data.result.completion', ['value' => 1]);
                } else if ($completion == 'incomplete') {
                    $this->addBoolFilterCondition($args, 'result.completion', 'data.result.completion', ['value' => 0]);
                } else if ($completion == 'undefined') {
                    $this->addNotExistsFilterCondition($args, 'result.completion', 'data.result.completion');
                } else if ($completion == 'defined') {
                    $this->addExistsFilterCondition($args, 'result.completion', 'data.result.completion');
                }
            }
            
            // Success
            if ($success = $this->getFilterValue($args, 'result.success')) {
                if ($success == 'passed') {
                    $this->addBoolFilterCondition($args, 'result.success', 'data.result.success', ['value' => 1]);
                } else if ($success == 'failed') {
                    $this->addBoolFilterCondition($args, 'result.success', 'data.result.success', ['value' => 0]);
                } else if ($success == 'undefined') {
                    $this->addNotExistsFilterCondition($args, 'result.success', 'data.result.success');
                } else if ($success == 'defined') {
                    $this->addExistsFilterCondition($args, 'result.success', 'data.result.success');
                }
            }
            
            // Score
            if ($scoreType = $this->getFilterValue($args, 'result.score_type')) {
                if ($scoreType == 'raw' || $scoreType == 'scaled') {
                    if (($scoreOperator = $this->getFilterValue($args, 'result.score_operator'))
                        && ($scoreValue = $this->getFilterValue($args, 'result.score_value'))
                        && in_array($scoreOperator, ['=', '>', '>=', '<', '<='])
                    ) {
                        $this->addNumFilterCondition($args, 'result.score_type', 'data.result.score.'.$scoreType, ['value' => $scoreValue], $scoreOperator);
                    }
                } else if ($scoreType == 'undefined') {
                    $this->addNotExistsFilterCondition($args, 'result.score_type', 'data.result.score');
                } else if ($scoreType == 'defined') {
                    $this->addExistsFilterCondition($args, 'result.score_type', 'data.result.score');
                }
            }
            
            // Context activity
            if ($activity = $this->getFilterValue($args, 'context.activity_id')) {
                if ($relation = $this->getFilterValue($args, 'context.activity_relation')) {

                    // With a given relation
                    $this->addEqualFilterCondition($args, 'context.activity_id', 'data.context.contextActivities.'.$relation.'[*].id');
                } else {

                    // All relations
                    $this->addEqualFilterCondition($args, 'context.activity_id', [
                        'data.context.contextActivities.parent[*].id',
                        'data.context.contextActivities.grouping[*].id',
                        'data.context.contextActivities.category[*].id',
                        'data.context.contextActivities.other[*].id'
                    ]);
                }
            }

            // Registration
            $this->addEqualFilterCondition($args, 'context.registration_id', 'data.context.registration');

            // Statement ID
            $this->addEqualFilterCondition($args, 'statementId', 'data.id');
            
            // Voided
            if ($voided = $this->getFilterValue($args, 'voided')) {
                $this->addEqualFilterCondition($args, 'voided', 'voided', ['value' => ($voided == 'voided')]);
            }

            // Attachments
            if ($attachments = $this->getFilterValue($args, 'attachments')) {
                if ($attachments == 'with') {
                    $this->addExistsFilterCondition($args, 'attachments', 'data.attachments');
                } else if ($attachments == 'without') {
                    $this->addNotExistsFilterCondition($args, 'attachments', 'data.attachments');
                }
            }

            // Time filters
            $this->addSinceFilterCondition($args, 'timestamp_since', 'data.timestamp');
            $this->addUntilFilterCondition($args, 'timestamp_until', 'data.timestamp');
            $this->addSinceFilterCondition($args, 'stored_since', 'data.stored');
            $this->addUntilFilterCondition($args, 'stored_until', 'data.stored');

            // Free filters arg
            unset($args['filters']);

            //print_r($args);die;
        }
        return parent::get($args, $options);
    }

    
}
