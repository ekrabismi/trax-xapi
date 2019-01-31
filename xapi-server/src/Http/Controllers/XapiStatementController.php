<?php

namespace Trax\XapiServer\Http\Controllers;

use Illuminate\Http\Request;
use DB;

use Trax\XapiServer\XapiServerServices;
use Trax\XapiServer\Http\Validations\XapiStatementValidation;
use Trax\DataStore\Http\Responses\MultipartResponse;
use Trax\DataStore\Exceptions\NotFoundException;

traxCreateStoreControllerSwitchClass('Trax\XapiServer\Http\Controllers', 'XapiStatement');

class XapiStatementController extends XapiStatementControllerSwitch
{
    use XapiStatementValidation, MultipartResponse;
    
    
    /**
     * Attachments store.
     */
    protected $attachmentStore;

    /**
     * Agents store.
     */
    protected $agentStore;

    /**
     * Activities store.
     */
    protected $activityStore;

    
    /**
     * Construct.
     */
    public function __construct(XapiServerServices $services)
    {
        $this->services = $services;
        $this->store = $this->services->xapiStatements();

        // Dependent Stores
        $this->attachmentStore = $this->services->attachments();
        $this->agentStore = $this->services->xapiAgents();
        $this->activityStore = $this->services->xapiActivities();
        
        // Middlewares
        if (traxRunningInLumen()) {
            $this->middleware('xapi.statement', ['only' => ['store', 'storeOne']]);
        } else {
            $this->middleware('xapi.statement')->only('store', 'storeOne');
        }
    }

    /**
     * Get data entries.
     */
    public function get(Request $request)
    {
        // Find
        if ($request->has('statementId') || $request->has('voidedStatementId')) {
            return $this->findBy($request);
        }

        // Permission
        $this->allowsRead($request);

        // Request validation
        $this->validateGetRequest($request);
        
        // Get statements
        $options = array();
        $args = $request->all();
        if (traxHasHeader($request, 'Accept-Language')) $options['lang'] = traxHeader($request, 'Accept-Language');
        $options['url'] = $request->url();
        $statements = $this->store->get($args, $options);

        // Response
        $joinAttachments = $request->has('attachments') && traxBool($request->input('attachments'));
        return $this->statementsResponse($statements, $joinAttachments);
    }
    
    /**
     * Find a data entry.
     */
    public function findBy(Request $request)
    {
        // Permissions
        $this->allowsRead($request);

        // Request validation
        $this->validateFindByRequest($request);
        
        // Get statement
        $options = array();
        if (traxHasHeader($request, 'Accept-Language')) $options['lang'] = traxHeader($request, 'Accept-Language');
        if ($request->has('format')) $options['format'] = $request->input('format');
        try {
            if ($request->has('statementId')) {
                $statement = $this->store->findBy('data.id', $request->input('statementId'), $options);
            } else {
                $options['voided'] = true;
                $statement = $this->store->findBy('data.id', $request->input('voidedStatementId'), $options);
            }
        } catch(NotFoundException $e) {
            $consistentThrough = $this->store->consistentThrough();
            return response($e->getMessage(), 404)->header('X-Experience-API-Consistent-Through', $consistentThrough);
        }
        
        // Response
        $joinAttachments = $request->has('attachments') && traxBool($request->input('attachments'));
        return $this->statementsResponse($statement, $joinAttachments);
    }
    
    /**
     * Store a data entry and return its ID.
     */
    public function store(Request $request)
    {
        // Alternate request
        if ($request->has('method')) {
            if ($request->input('method') == 'GET') return $this->get($request);
            if ($request->input('method') == 'PUT') return $this->storeOne($request);
        }

        // Permissions
        $this->allowsCreate($request);

        // Request validation
        $this->validateStoreRequest($request);
        list($statements, $attachments, $agents, $activities) = $this->validateStoreContent($request);
        
        // Start Transaction
        $res = DB::transaction(function () use ($statements, $attachments, $agents, $activities) {

            // Should perform a single request per store !!!!!!!!!!!!!!!!!!!!!!!

            // Store statements
            $res = array();
            if (is_array($statements)) {
                foreach($statements as $statement) {
                    $res[] = $this->store->store($statement);
                }
            } else {
                $res[] = $this->store->store($statements);
            }        

            // Store attachments
            foreach($attachments as $attachment) {
                $this->attachmentStore->store($attachment);
            }
            
            // Store activities
            foreach($activities as $activity) {
                $this->activityStore->store($activity);
            }
            
            // Store agents
            foreach($agents as $agent) {
                $this->agentStore->store($agent);
            }

            return $res;
        });
        // End of transaction

        // Response
        return response()->json($res);
    }
    
    /**
     * Store a single data entry and return its ID.
     */
    public function storeOne(Request $request)
    {
        // Request validation
        $this->validateStoreOneRequest($request);
        list($statements, $attachments, $agents, $activities) = $this->validateStoreOneContent($request);
        
        // Store statements
        $id = $this->store->storeOne($request->input('statementId'), $statements);
        
        // Store attachments
        foreach($attachments as $attachment) {
            $this->attachmentStore->store($attachment);
        }
        
        // Store activities
        foreach($activities as $activity) {
            $this->activityStore->store($activity);
        }
        
        // Store agents
        foreach($agents as $agent) {
            $this->agentStore->store($agent);
        }
        
        // Response
        return response('', 204);
    }


    /**
     * Return statements response.
     */
    protected function statementsResponse($statements, $joinAttachments = false)
    {
        if ($joinAttachments) {

            // Multipart
            $justStatements = isset($statements->statements) ? $statements->statements : $statements;
            $attachments = $this->attachmentStore->get(['statements' => $justStatements])->all();
            array_unshift($attachments, (object)array('content' => json_encode($statements)));
            $response = $this->multipartResponse($attachments);

        } else {
            
            // JSON
            $response = response()->json($statements);
        }
        $consistentThrough = $this->store->consistentThrough();
        return $response->header('X-Experience-API-Consistent-Through', $consistentThrough);
    }
    
}
