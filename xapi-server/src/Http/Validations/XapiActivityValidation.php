<?php

namespace Trax\XapiServer\Http\Validations;

use Illuminate\Http\Request;

trait XapiActivityValidation
{
    use XapiValidation;

    
    /**
     * Validate GET request.
     */
    protected function validateGetRequest(Request $request)
    {
        // Validation rules
        $rules = [
            'activityId' => 'required|url',                    
        ];
        
        // Do it
        $this->validateRequest($request, [
            'rules' => $rules,
            'limited_to' => $this->inputsToValidate($request, $rules),
        ]);
    }
    
}
