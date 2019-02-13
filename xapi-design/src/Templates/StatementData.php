<?php

namespace Trax\XapiDesign\Templates;

use Trax\XapiDesign\Contracts\XapiTranslator;
use Trax\XapiDesign\Contracts\XapiVocabIndex;
use XapiDesign;

abstract class StatementData
{
    /**
     * Actor data.
     */
    protected $actor;

    /**
     * Activity data.
     */
    protected $activity;

    /**
     * Result data.
     */
    protected $result;

    /**
     * Context data.
     */
    protected $context;

    /**
     * Statement data (sub-statement or statement-ref).
     */
    protected $statement;


    /**
     * Set actor translation.
     */
    protected function setActor(XapiTranslator $translator)
    {
        $this->actor = $translator->rawTranslation();
    }

    /**
     * Set activity translation.
     */
    protected function setActivity(XapiTranslator $translator)
    {
        $this->activity = $translator->rawTranslation();
    }

    /**
     * Set result translation.
     */
    protected function setResult(XapiTranslator $translator)
    {
        $this->result = $translator->rawTranslation();
    }

    /**
     * Set context translation.
     */
    protected function setContext(XapiTranslator $translator)
    {
        $this->context = $translator->rawTranslation();
    }

    /**
     * Set statement translation.
     */
    protected function setStatement(XapiTranslator $translator)
    {
        $this->statement = $translator->rawTranslation();
    }

    /**
     * Platform IRI: project specific. Will not change if the hosting domain changes.
     */
    abstract protected function platformIri();

    /**
     * Get the platform.
     */
    abstract protected function platformName();

    /**
     * Get the activity prefix.
     */
    abstract protected function activityPrefix();

    /**
     * Get the agent home page.
     */
    protected function accountHomepage()
    {
        return $this->platformIri();
    }

    /**
     * Get the agent account name.
     */
    protected function accountName()
    {
        return $this->prop('actor', 'accountName');
    }

    /**
     * Get the verb id.
     */
    abstract protected function verbId();

    /**
     * Get the activity id.
     */
    protected function activityId()
    {
        return $this->prop('activity', 'id');
    }

    /**
     * Get the activity type.
     */
    protected function activityType()
    {
        return $this->prop('activity', 'type');
    }

    /**
     * Get the activity name.
     */
    protected function activityName()
    {
        return $this->prop('activity', 'name');
    }

    /**
     * Get the activity description.
     */
    protected function activityDescription()
    {
        return $this->prop('activity', 'description');
    }

    /**
     * Get the activity moreInfo.
     */
    protected function activityMoreInfo()
    {
        return $this->prop('activity', 'moreInfo');
    }

    /**
     * Get the activity extensions.
     */
    protected function activityExtensions()
    {
        return $this->prop('activity', 'extensions', []);
    }

    /**
     * Get the object statement ref.
     */
    protected function statementRef()
    {
        return $this->prop('statement', 'ref');
    }

    /**
     * Get the completion.
     */
    protected function completion()
    {
        return $this->prop('result', 'completion');
    }

    /**
     * Get the success.
     */
    protected function success()
    {
        return $this->prop('result', 'success');
    }

    /**
     * Get the score scaled.
     */
    protected function scoreScaled()
    {
        return $this->prop('result', 'scoreScaled');
    }

    /**
     * Get the score raw.
     */
    protected function scoreRaw()
    {
        return $this->prop('result', 'scoreRaw');
    }

    /**
     * Get the score min.
     */
    protected function scoreMin()
    {
        return $this->prop('result', 'scoreMin');
    }

    /**
     * Get the score max.
     */
    protected function scoreMax()
    {
        return $this->prop('result', 'scoreMax');
    }

    /**
     * Get the duration.
     */
    protected function duration()
    {
        return $this->prop('result', 'duration');
    }

    /**
     * Get the response.
     */
    protected function response()
    {
        return $this->prop('result', 'response');
    }

    /**
     * Get the result extensions.
     */
    protected function resultExtensions()
    {
        return $this->prop('result', 'extensions', []);
    }

    /**
     * Get the registration.
     */
    protected function registration()
    {
        return $this->prop('context', 'registration');
    }

    /**
     * Get the instructor(s).
     */
    protected function instructor()
    {
        return $this->prop('context', 'instructor');
    }

    /**
     * Get the team.
     */
    protected function team()
    {
        return $this->prop('context', 'team');
    }

    /**
     * Get the revision.
     */
    protected function revision()
    {
        return $this->prop('context', 'revision');
    }

    /**
     * Get the language.
     */
    protected function language()
    {
        return $this->prop('context', 'language');
    }

    /**
     * Get the context activities.
     */
    protected function contextActivities()
    {
        $parent = $this->contextParent();
        $grouping = $this->contextGrouping();
        $category = $this->contextCategory();
        $other = $this->contextOther();
        $res = [];
        if (!empty($parent)) $res['parent'] = $parent;
        if (!empty($grouping)) $res['grouping'] = $grouping;
        if (!empty($category)) $res['category'] = $category;
        if (!empty($other)) $res['other'] = $other;
        return $res;
    }

    /**
     * Get the context categories.
     */
    protected function contextCategory()
    {
        return $this->prop('context', 'categoryActivities', []);
    }

    /**
     * Get the context parents.
     */
    protected function contextParent()
    {
        return $this->prop('context', 'parentActivities', []);
    }

    /**
     * Get the context groupings.
     */
    protected function contextGrouping()
    {
        return $this->prop('context', 'groupingActivities', []);
    }

    /**
     * Get the context other.
     */
    protected function contextOther()
    {
        return $this->prop('context', 'otherActivities', []);
    }

    /**
     * Get the context extensions.
     */
    protected function contextExtensions()
    {
        return $this->prop('context', 'extensions', []);
    }

    /**
     * Get the timestamp.
     */
    protected function timestamp()
    {
        return null;
    }

    /**
     * Get prop.
     */
    protected function prop($part, $prop, $default = null)
    {
        return isset($this->$part) && isset($this->$part->$prop) ? $this->$part->$prop : $default;
    }

}
