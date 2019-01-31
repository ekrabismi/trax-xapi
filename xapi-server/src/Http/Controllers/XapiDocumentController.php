<?php

namespace Trax\XapiServer\Http\Controllers;

use Illuminate\Http\Request;

trait XapiDocumentController
{
    
    /**
     * Get data entries.
     */
    public function get(Request $request)
    {
        $this->allowsRead($request);
        $this->validateGetRequest($request);
        list($content, $type) = $this->getRequest($request);
        return $this->getResponse($content, $type);
    }

    /**
     * Get GET request.
     */
    protected function getRequest($request)
    {
        $scope = $request->all();
        if ($request->has($this->documentId)) {
            
            // Get a single document
            $data = $this->store->getOne($request->input($this->documentId), $scope)->data;
            return array($data->document, $data->contentType);
        } else {
            
            // Get a list of IDs
            $documentIds = $this->store->get([], $scope);
            return array($documentIds, 'application/json');
        }
    }
    
    /**
     * Get GET response.
     */
    protected function getResponse($content, $type = 'application/json')
    {
        // Response base
        if ($type == 'application/json') {
            $response = response()->json($content);
            $content = json_encode($content);  // For the next step
        } else {
            $response = response($content, 200)
                        ->header('Content-Type', $type)
                        ->header('Content-Length', mb_strlen($content, '8bit'));
        }
        
        // ETag
        // Should check this:
        // An LRS responding to a GET request using any non-identity transfer encoding MUST NOT calculate the included ETag as above,
        // due to the interpretation of ETags by existing web infrastructure.
        if (isset($this->concurrency) && $this->concurrency)
            $response = $response->header('ETag', '"'.sha1($content).'"');
        
        return $response;
    }
    
    /**
     * Store a data entry and return its ID.
     */
    public function store(Request $request)
    {
        // Alternate request
        if ($request->has('method')) {
            if ($request->input('method') == 'GET') return $this->get($request);
            if ($request->input('method') == 'PUT') return $this->update($request);
            if ($request->input('method') == 'DELETE') return $this->delete($request);
        }

        // Permissions
        $this->allowsCreate($request);

        // Request validation
        $this->validateStoreRequest($request);
        list($document, $type) = $this->validateStoreContent($request);
        
        // Operation
        $scope = $request->all();
        $scope = array_merge($scope, $request->query());
        $scope['contentType'] = $type;
        $scope['canMerge'] = true;
        $this->store->store($document, $scope);
        return response('', 204);
    }
    
    /**
     * Update a data entry with a PUT request.
     */
    public function update(Request $request, $id = null)
    {
        // Permissions
        $this->allowsUpdate($request, $id);

        // Request validation
        $this->validateStoreRequest($request);
        list($document, $type) = $this->validateStoreContent($request);
        
        // Concurrency
        if (isset($this->concurrency) && $this->concurrency) {
            $existingDocument = false;
            try {
                list($existingDocument, $existingType) = $this->getRequest($request);            
            } catch (\Exception $e) {
            }
            $this->validateConcurrency($request, $document, $existingDocument);
        }
        
        // Operation
        $scope = $request->all();
        $scope = array_merge($scope, $request->query());
        $scope['contentType'] = $type;
        $this->store->store($document, $scope);
        return response('', 204);
    }
    
    /**
     * Delete a data entry.
     */
    public function delete(Request $request, $id = null)
    {
        // Permissions
        $this->allowsDelete($request, $id);

        // Request validation
        $this->validateDeleteRequest($request);
        
        // Operation
        $scope = $request->all();
        if ($request->has($this->documentId)) {
            
            // Delete a single document
            $this->store->deleteOne($request->input($this->documentId), $scope);
            
        } else {
            
            // Delete all the documents
            $this->store->deleteAll($scope);
        }
        return response('', 204);
    }
    
}
