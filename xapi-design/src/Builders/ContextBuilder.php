<?php

namespace Trax\XapiDesign\Builders;

class ContextBuilder
{
    use BuilderUtilities;

    /**
     * Parent statement.
     */
    protected $statement;

    /**
     * Current xAPI result.
     */
    protected $context = [];


    /**
     * Specify the parent Statement.
     */
    public function parentStatement($statement)
    {
        $this->statement = $statement;
        return $this;
    }

    /**
     * Set the registration.
     */
    public function registration($registration = null)
    {
        if (!isset($registration)) return $this->object();
        $this->context['registration'] = $registration;
        return $this->object();
    }

    /**
     * Set the instructor.
     */
    public function instructor($instructor = null)
    {
        if (!isset($instructor)) return $this->object();
        $this->context['instructor'] = $instructor;
        return $this->object();
    }

    /**
     * Set the team.
     */
    public function team($team = null)
    {
        if (!isset($team)) return $this->object();
        $this->context['team'] = $team;
        return $this->object();
    }

    /**
     * Set the revision.
     */
    public function revision($revision = null)
    {
        if (!isset($revision)) return $this->object();
        $this->context['revision'] = $revision;
        return $this->object();
    }

    /**
     * Set the platform.
     */
    public function platform($platform = null)
    {
        if (!isset($platform)) return $this->object();
        $this->context['platform'] = $platform;
        return $this->object();
    }

    /**
     * Set the language.
     */
    public function language($language = null)
    {
        if (!isset($language)) return $this->object();
        $this->context['language'] = $language;
        return $this->object();
    }

    /**
     * Set the statement.
     */
    public function statement($id = null)
    {
        if (!isset($id)) return $this->object();
        $this->context['statement'] = [
            'objectType' => 'StatementRef',
            'id' => $id,
        ];
        return $this->object();
    }

    /**
     * Set the contextActivities.
     */
    public function contextActivities($contextActivities = [])
    {
        if (empty($contextActivities)) return $this->object();
        $this->context['contextActivities'] = $contextActivities;
        return $this->object();
    }

    /**
     * Set the extensions.
     */
    public function extensions($extensions = [])
    {
        if (empty($extensions)) return $this->object();
        $this->context['extensions'] = $extensions;
        return $this->object();
    }

    /**
     * Get the result and clear.
     */
    public function get()
    {
        $res = $this->context;
        $this->context = [];
        return $res;
    }

}
