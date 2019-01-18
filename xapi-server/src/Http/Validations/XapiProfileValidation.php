<?php

namespace Trax\XapiServer\Http\Validations;

use Illuminate\Http\Request;

trait XapiProfileValidation
{
    use XapiDocumentValidation;

    
    /**
     * Validate destroy request.
     */
    protected function validateDeleteRequest(Request $request, $id = null)
    {
        $rules = $this->validationRules();
        $this->validateRequest($request, [
            'rules' => $rules,
            'limited_to' => $this->inputsToValidate($request, $rules),
        ]);
    }
    
}
