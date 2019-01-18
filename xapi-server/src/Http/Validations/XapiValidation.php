<?php

namespace Trax\XapiServer\Http\Validations;

trait XapiValidation
{
    
    /**
     * Accepted additional inputs for alternate methods
     */
    protected $alternateInputs = [  'method',
                                    'Accept',
                                    'Accept-Encoding',
                                    'Accept-Language',
                                    'Authorization',
                                    'Content-Type',
                                    'Content-Length',
                                    'Content-Transfer-Encoding',
                                    'X-Experience-API-Version',
                                    'If-Match',
                                    'If-None-Match',
                                  ];

    /**
     * Inputs to be validated.
     */
    protected function inputsToValidate($request, $rules, $additional = array())
    {
        $limitedTo = array_keys($rules);
        $limitedTo = array_merge($limitedTo, $additional);
        if ($request->has('method'))
            $limitedTo = array_merge($limitedTo, $this->alternateInputs);
        return $limitedTo;
    }
    
    
}


