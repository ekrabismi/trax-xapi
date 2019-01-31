<?php

namespace Trax\XapiServer\Http\Validations;

use Illuminate\Http\Request;

trait XapiAgentValidation
{
    use XapiValidation;

    
    /**
     * Validate GET request.
     */
    protected function validateGetRequest(Request $request)
    {
        // Validation rules
        $rules = [
            'agent' => 'required|xapi_agent',                
        ];
        
        // Do it
        $this->validateRequest($request, [
            'rules' => $rules,
            'limited_to' => $this->inputsToValidate($request, $rules),
        ]);
    } 
    
}
