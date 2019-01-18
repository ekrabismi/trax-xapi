<?php

namespace Trax\XapiServer\Http\Validations;

use Illuminate\Http\Request;

use Trax\XapiServer\Exceptions\XapiHeaderException;
use Trax\XapiServer\Exceptions\XapiContentException;

trait XapiDocumentValidation
{
    use XapiValidation;

    
    /**
     * Validate GET request.
     */
    protected function validateGetRequest(Request $request)
    {
        $rules = $this->validationRules(false, true);
        $this->validateRequest($request, [
            'rules' => $rules,
            'limited_to' => $this->inputsToValidate($request, $rules),
        ]);
    }
    
    /**
     * Validate store request.
     */
    protected function validateStoreRequest(Request $request)
    {
        $rules = $this->validationRules();
        $this->validateRequest($request, [
            'rules' => $rules,
            'limited_to' => $this->inputsToValidate($request, $rules, ['content']),
        ]);
    }
    
    /**
     * Validate destroy request.
     */
    protected function validateDeleteRequest(Request $request, $id = null)
    {
        $rules = $this->validationRules(false);
        $this->validateRequest($request, [
            'rules' => $rules,
            'limited_to' => $this->inputsToValidate($request, $rules),
        ]);
    }
    
    /**
     * Get validation rules.
     */
    protected function validationRules($idRequired = true, $since = false)
    {
        $rules = [];
        
        // Activity
        if (in_array('activity', $this->documentFields)) $rules['activityId'] = 'required|url';
        
        // Agent    
        if (in_array('agent', $this->documentFields)) $rules['agent'] = 'required|xapi_agent';
            
        // Registration    
        if (in_array('registration', $this->documentFields)) $rules['registration'] = 'uuid';
        
        // ID
        $rules[$this->documentId] = 'string|forbidden_with:since';
        if ($idRequired) $rules[$this->documentId] = 'required|'.$rules[$this->documentId];
        
        // Since
        if ($since) $rules['since'] = 'iso_date|forbidden_with:'.$this->documentId;
        
        return $rules;
    }
    
    /**
     * Validate store request content.
     */
    protected function validateStoreContent(Request $request)
    {
        if ($request->isJson()) {
            
            // JSON content
            $content = traxContent($request);
            $document = json_decode($content, true);
            if (!$document) throw new XapiContentException('Invalid JSON content.');
            return array($document, 'application/json');
        
        } else if (traxHasHeader($request, 'Content-Type')) {
            
            // Other content
            $content = traxContent($request);
            return array($content, traxHeader($request, 'Content-Type'));
        
        } else {

            // Missing content type
            throw new XapiHeaderException('Missing Content-Type header.');
        }
    }
        
    /**
     * Validate concurrency.
     */
    protected function validateConcurrency($request, $newDocument, $existingDocument)
    {
        // If-Match
        if (traxHasHeader($request, 'If-Match')) {
            if ($existingDocument === false) {
                throw new XapiHeaderException('If-Match header does not match with the existing content.', 412);
            } else {                
                if (!is_string($existingDocument)) $existingDocument = json_encode($existingDocument);
                if (traxHeader($request, 'If-Match') != '"'.sha1($existingDocument).'"') {
                    throw new XapiHeaderException('If-Match header does not match with the existing content.', 412);
                } else {
                    return true;
                }
            }
        }
        
        // If-None-Match
        if (traxHasHeader($request, 'If-None-Match')) {
            if (traxHeader($request, 'If-None-Match') != '*') {
                throw new XapiHeaderException('Concurrency header If-None-Match must be *.', 409);
            } else if ($existingDocument !== false) {
                throw new XapiHeaderException('If-None-Match is set to * but there is an existing content.', 412);
            } else {
                return true;
            }
        }
        
        // Missing concurrency data
        if ($existingDocument !== false)
            throw new XapiHeaderException('Missing concurrency header If-Match of If-None-Match.', 409);
        
        throw new XapiHeaderException('Missing concurrency header If-Match of If-None-Match.', 400);
    }
    
}
