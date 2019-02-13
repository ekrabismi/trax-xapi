<?php

namespace Trax\XapiDesign\Builders;

use Faker\Factory;

use Trax\XapiDesign\Contracts\XapiVocabIndex;
use Trax\XapiDesign\Exceptions\XapiBuilderException;
use Trax\XapiDesign\Exceptions\XapiVocabException;

class StatementBuilder
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
     * xAPI client.
     */
    protected $client;

    /**
     * Activity prefix.
     */
    protected $activityPrefix = 'http://xapi.fr/activities/';

    /**
     * The statement.
     */
    protected $statement = [];

    /**
     * Current section.
     */
    protected $currentSection;

    /**
     * Current builder.
     */
    protected $currentBuilder;

    /**
     * Faker
     */
    protected $faker;

    /**
     * The statement test data.
     */
    protected $test;

    
    /**
     * Construct.
     */
    public function __construct($client = null)
    {
        $this->client = $client;
        $this->lang = config('trax-xapi-client.xapi.lang');
        $this->faker = Factory::create();
    }

    /**
     * Catch methods and redirect to builders when relevant.
     */
    public function __call($methodName, $args)
    {
        if (isset($this->currentBuilder) && method_exists($this->currentBuilder, $methodName)) {
            return call_user_func_array(array($this->currentBuilder, $methodName), $args);
        } else {
            return call_user_func_array(array($this, '_'.$methodName), $args);
        }
    }

    /**
     * Specify the vocab index.
     */
    public function _vocab(XapiVocabIndex $vocab = null)
    {
        $this->vocab = $vocab;
        return $this;
    }

    /**
     * Specify the lang for lang strings.
     */
    public function _lang($lang)
    {
        $this->lang = $lang;
        return $this;
    }

    /**
     * Specify the activity ID prefix.
     */
    public function _activityPrefix($prefix)
    {
        $this->activityPrefix = $prefix;
        return $this;
    }

    /**
     * Specify a statement template.
     */
    public function _template($template)
    {
        $this->statement = $template;
        return $this;
    }

    /**
     * Return an agent builder.
     */
    public function _agentBuilder()
    {
        return (new AgentBuilder());
    }

    /**
     * Return a group builder.
     */
    public function _groupBuilder()
    {
        return (new GroupBuilder());
    }

    /**
     * Return a verb builder.
     */
    public function _verbBuilder()
    {
        return (new VerbBuilder())
            ->vocab($this->vocab)
            ->lang($this->lang);
    }

    /**
     * Return an activity builder.
     */
    public function _activityBuilder()
    {
        return (new ActivityBuilder())
            ->vocab($this->vocab)
            ->lang($this->lang)
            ->prefix($this->activityPrefix);
    }

    /**
     * Return an result builder.
     */
    public function _resultBuilder()
    {
        return (new ResultBuilder());
    }

    /**
     * Return a context builder.
     */
    public function _contextBuilder()
    {
        return (new ContextBuilder());
    }

    /**
     * Return attachments builder.
     */
    public function _attachmentsBuilder()
    {
        return (new AttachmentsBuilder())
            ->vocab($this->vocab)
            ->lang($this->lang);
    }

    /**
     * Set the actor with raw data.
     */
    public function _actor($actor)
    {
        $this->statement['actor'] = $actor;
        $this->newSection('actor');
        return $this;
    }

    /**
     * Set the agent (actor or object).
     */
    public function _agent()
    {
        if (!isset($this->statement['verb'])) $this->newSection('actor', 'agent');
        else $this->newSection('object', 'agent');
        return $this;
    }

    /**
     * Set the group (actor or object).
     */
    public function _group()
    {
        if (!isset($this->statement['verb'])) $this->newSection('actor', 'group');
        else $this->newSection('object', 'group');
        return $this;
    }

    /**
     * Set the verb.
     */
    public function _verb($verb = null)
    {
        if (isset($verb)) {
            $this->statement['verb'] = $verb;
            $this->newSection('verb');
        } else {
            $this->newSection('verb', 'verb');
        }
        return $this;
    }

    /**
     * Set the activity.
     */
    public function _activity()
    {
        $this->newSection('object', 'activity');
        return $this;
    }

    /**
     * Set the interaction.
     */
    public function _interaction()
    {
        $this->newSection('object', 'activity');
        $this->currentBuilder->interaction();
        return $this;
    }

    /**
     * Set the object with raw data.
     */
    public function _object($object)
    {
        $this->statement['object'] = $object;
        $this->newSection('object');
        return $this;
    }

    /**
     * Set the statement ref.
     */
    public function _statementRef($id)
    {
        return $this->_object([
            'objectType' => 'StatementRef',
            'id' => $id,
        ]);
    }

    /**
     * Set the sub statement.
     */
    public function _subStatement($statement)
    {
        $statement['objectType'] = 'SubStatement';
        return $this->_object($statement);
    }

    /**
     * Set the result.
     */
    public function _result()
    {
        $this->newSection('result', 'result');
        return $this;
    }

    /**
     * Set the context.
     */
    public function _context()
    {
        $this->newSection('context', 'context');
        return $this;
    }

    /**
     * Set attachments.
     */
    public function _attachments($attachments = null)
    {
        if (isset($attachments)) {
            if (empty($attachments)) return $this;
            $this->statement['attachments'] = $attachments;
            $this->newSection('attachments');
        } else {
            $this->newSection('attachments', 'attachments');
        }
        return $this;
    }

    /**
     * Specify or generate the statement ID.
     */
    public function _id($id = null)
    {
        $this->newSection();
        if (isset($id)) $this->statement['id'] = $id;
        else $this->statement['id'] = $this->faker->uuid;
        return $this;
    }

    /**
     * Set the timestamp.
     */
    public function _timestamp($timestamp = null)
    {
        $this->newSection();
        if (!isset($timestamp)) $timestamp = date('c');
        $this->statement['timestamp'] = $timestamp;
        return $this;
    }

    /**
     * Set the version.
     */
    public function _version($version = null)
    {
        $this->newSection();
        if (!isset($version)) $version = config('trax-xapi-client.xapi.version');
        $this->statement['version'] = $version;
        return $this;
    }

    /**
     * Set the authority.
     */
    public function _authority($authority = null)
    {
        if (!isset($authority)) return $authority;
        $this->newSection();
        $this->statement['authority'] = $authority;
        return $this;
    }

    /**
     * Set test data.
     */
    public function _test($data)
    {
        $this->newSection();
        $this->test = $data;
        return $this;
    }

    /**
     * Get statement in a string format string.
     */
    public function get()
    {
        $this->newSection();
        $res = $this->statement;
        if ($this->test) $res['_test'] = $this->test;
        $this->clear();        
        return $res;
    }

    /**
     * Post the statement.
     */
    public function _post($testCase = null)
    {
        if (!isset($this->client)) throw new XapiBuilderException('xAPI client not defined.');
        return $this->client->statements()->post($this->get())->send($testCase);
    }

    /**
     * Put the statement.
     */
    public function _put($statementId = null, $testCase = null)
    {
        if (!isset($this->client)) throw new XapiBuilderException('xAPI client not defined.');
        return $this->client->statements()->put($this->get(), $statementId)->send($testCase);
    }

    /**
     * Record the statement.
     */
    public function _record()
    {
        if (!isset($this->client)) throw new XapiBuilderException('xAPI client not defined.');
        return $this->client->localStatements()->record($this->get());
    }

    /**
     * Clean statement data.
     */
    public function _clear()
    {
        $this->statement = [];
        $this->test = null;
        $this->newSection();
        return $this;
    }

    /**
     * Start a new Statement section.
     */
    protected function newSection($section = null, $builderName = null)
    {
        // Record previous builder data
        if (isset($this->currentBuilder)) {
            $insert = $this->currentBuilder->get();
            if (!empty($insert)) $this->statement[$this->currentSection] = $insert;
        }
        // Reset builder
        $this->currentSection = $section;
        $this->currentBuilder = null;

        // Create new builder
        if (isset($builderName)) {
            $builderFunction = $builderName.'Builder';
            $this->currentBuilder = $this->$builderFunction()->parentStatement($this);
        }
    }

}
