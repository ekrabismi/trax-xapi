<?php

namespace Trax\XapiServer\Http\Validations;

use Illuminate\Http\Request;

use Trax\XapiServer\XapiStatementValidator;
use Trax\XapiServer\XapiStatementComparator;
use Trax\XapiServer\Exceptions\XapiRequestException;
use Trax\XapiServer\Exceptions\XapiContentException;
use Trax\DataStore\Http\Requests\MultipartRequest;

trait XapiStatementValidation
{
    use MultipartRequest, XapiValidation;

    
    /**
     * Validate GET request.
     */
    protected function validateGetRequest(Request $request)
    {
        // Validation rules
        $rules = [
            'agent' => 'xapi_agent',                
            'verb' => 'url',                        
            'activity' => 'url',                    
            'registration' => 'uuid',
            'related_activities' => 'boolean_flex',
            'related_agents' => 'boolean_flex',
            'since' => 'iso_date',
            'until' => 'iso_date',
            'limit' => 'integer|min:0',
            'format' => 'xapi_format',              
            'attachments' => 'boolean_flex',
            'ascending' => 'boolean_flex',
        ];
        
        // Do it
        $this->validateRequest($request, [
            'rules' => $rules,
            'errors' => $this->exceptionConsistentThrough(),
            'limited_to' => $this->inputsToValidate($request, $rules, ['from']),
        ]);
    }
    
    /**
     * Validate find by request.
     */
    protected function validateFindByRequest(Request $request)
    {
        // Validation rules
        $rules = [
            'statementId' => 'uuid|forbidden_with:voidedStatementId',
            'voidedStatementId' => 'uuid|forbidden_with:statementId',
            'format' => 'xapi_format',              
            'attachments' => 'boolean_flex',
        ];
        
        // Do it
        $this->validateRequest($request, [
            'rules' => $rules,
            'errors' => $this->exceptionConsistentThrough(),
            'limited_to' => $this->inputsToValidate($request, $rules),
        ]);
    }
    
    /**
     * Validate store request.
     */
    protected function validateStoreRequest(Request $request)
    {
        $this->validateRequest($request, [
            'rules' => [],
            'limited_to' => [],
        ]);
    }
    
    /**
     * Validate the storeOne request.
     */
    protected function validateStoreOneRequest(Request $request)
    {
        // Validation rules
        $rules = [
            'statementId' => 'required|uuid',   //|unique:'.traxConnection('trax-xapi-server', 'Statement').'.statements,data->id',
        ];
        
        // Do it
        $this->validateRequest($request, [
            'rules' => $rules,
            'errors' => [
                'statementId' => ['Unique' => new XapiRequestException('A statement with this UUID already exists.', 204)],
            ],
            'limited_to' => $this->inputsToValidate($request, $rules, ['content']),
        ]);

        // Now verify statementId unicity. Can't be done in the $rules because MariaDB does not support data->id syntax.
        try {
            $this->store->findBy('data.id', $request->input('statementId'), ['voided' => false]);
            throw new XapiRequestException('A statement with this UUID already exists.', 204);
        } catch(\Exception $e) {}
        try {
            $this->store->findBy('data.id', $request->input('statementId'), ['voided' => true]);
            throw new XapiRequestException('A statement with this UUID already exists.', 204);
        } catch(\Exception $e) {}

    }

    /**
     * Validate the store request content.
     */
    protected function validateStoreContent(Request $request)
    {
        // Get content from request
        list($statements, $attachments) = $this->contentToValidate($request);

        // Statements validation
        $this->validateStatements($statements);
        
        // Statement IDs
        $this->validateStatementIds($statements);
        
        // Attachments
        $this->validateAttachments($statements, $attachments);
        
        return array($statements, $attachments);
    }

    /**
     * Validate the store one request content.
     */
    protected function validateStoreOneContent(Request $request)
    {
        // Validate POST content
        list($statements, $attachments) = $this->validateStoreContent($request);

        // Statement batch
        if (is_array($statements))
            throw new XapiContentException('Array not accepted in PUT request.');
        
        // Consistent IDs between statementId and Statement->id
        if (isset($statements->id) && $statements->id != $request->input('statementId'))
            throw new XapiContentException('Inconsistent statement ID.');
        
        return array($statements, $attachments);
    }
    
    /**
     * Custom exception.
     */
    protected function exceptionConsistentThrough()
    {
        $headers = ['X-Experience-API-Consistent-Through' => $this->store->consistentThrough()];
        return new XapiRequestException('Invalid request params.', 400, $headers);
    }
    
    /**
     * Get the request content.
     */
    protected function contentToValidate(Request $request)
    {
        if ($this->isMultipart($request)) {
            
            // Multipart content
            $parts = $this->parts($request);
            $statements = json_decode(array_shift($parts)->content);
            $attachments = $parts;
        } else {
            
            // JSON content
            $content = traxContent($request);
            $statements = json_decode($content);
            $attachments = [];
        }
        return array($statements, $attachments);
    }
    
    /**
     * Validate Statements.
     */
    protected function validateStatements($statements)
    {
        // Statements batch
        if (is_array($statements)) {
            foreach($statements as $statement) {
                $this->validateStatements($statement);
            }
            return;
        }
        
        // Single statement
        $statement = $statements;
        $validator = new XapiStatementValidator();
        $validator->validate($statement);
    }
    
    /**
     * Check Statement IDs.
     */
    protected function validateStatementIds($statements, $reserved = array())
    {
        // Statements batch
        if (is_array($statements)) {
            $ids = array();
            foreach($statements as $statement) {
                $this->validateStatementIds($statement, $ids);
                if (isset($statement->id)) $ids[] = $statement->id;
            }
            return;
        }
        
        // No statement ID
        $statement = $statements;
        if (!isset($statement->id)) return;
        
        // ID already used in the batch
        if (in_array($statement->id, $reserved))
            throw new XapiContentException('ID already used in batch.');
        
        // ID already used in DB
        try {
            $recorded = $this->store->findBy('data.id', $statement->id);
        } catch (\Exception $e) {
            return true;
        }
        throw new XapiContentException('ID already used in store.');
    }
    
    /**
     * Validate attachments.
     */
    protected function validateAttachments($statements, $attachments = array(), $checkUnused = true)
    {
        $usedAttachments = [];
        if (is_array($statements)) {

            // Statements batch
            foreach($statements as $statement) {
                $justUsedAttachments = $this->validateAttachments($statement, $attachments, false);
                $usedAttachments = array_merge($usedAttachments, $justUsedAttachments);
            }
        } else {
            
            // Single statement
            $statement = $statements;
            if (!isset($statement->attachments)) return $usedAttachments;
            foreach($statement->attachments as $jsonAttachment) {
                
                // Location
                $isRemote = isset($jsonAttachment->fileUrl);
                $mustBeLocal = ($jsonAttachment->usageType == 'http://adlnet.gov/expapi/attachments/signature' || !$isRemote);
                
                // Check that a matching raw attachment exists
                if ($mustBeLocal && !isset($attachments[$jsonAttachment->sha2]))
                    throw new XapiContentException('Missing raw attachment.');
                
                // No local attachment
                if (!isset($attachments[$jsonAttachment->sha2])) continue;
                
                // Check that content type matches
                $rawAttachment = $attachments[$jsonAttachment->sha2];
                if (isset($rawAttachment->contentType) && $rawAttachment->contentType != $jsonAttachment->contentType)
                    throw new XapiContentException('Inconsistent attachment type.');
                
                // Check that content lenght matches
                if (isset($rawAttachment->length) && $rawAttachment->length != $jsonAttachment->length)
                    throw new XapiContentException('Inconsistent attachment length.');
                
                // Check signed statements
                $this->validateSignedAttachment($jsonAttachment, $rawAttachment, $statements);
                
                // Check that content length does not exceed the platform config
                // TBD !!! (MongoDB/MySQL limit, server limit...)
                
                // Remember the attachment as used
                $usedAttachments[$jsonAttachment->sha2] = true;
            }
        }
                
        // Some attachments have not been used
        if ($checkUnused && count($usedAttachments) < count($attachments))
            throw new XapiContentException('Unused attachment(s).');
            
        return $usedAttachments;
    }

    /**
     * Validate signed attachment.
     */
    protected function validateSignedAttachment($jsonAttachment, $rawAttachment, $statements)
    {
        // Not a signed statement
        if ($jsonAttachment->usageType != 'http://adlnet.gov/expapi/attachments/signature') return;
        
        // Check content type
        if ($jsonAttachment->contentType != 'application/octet-stream')
            throw new XapiContentException('Malformed signed statement, content type issue.');
        
        // Decode the attachment content
        $parts = explode('.', $rawAttachment->content);
        if (count($parts) != 3)
            throw new XapiContentException('Malformed signed statement, compact serialization issue.');

        // Check header
        $header = json_decode(base64_decode($parts[0]));
        if (!$header)
            throw new XapiContentException('Malformed signed statement, header serialization issue.');
        
        if (!isset($header->alg) || !in_array($header->alg, ['RS256', 'RS384', 'RS512']))
            throw new XapiContentException('Malformed signed statement, encryption algorythm not supported.');
        
        // Check payload
        $payload = json_decode(base64_decode($parts[1]));
        if (!$payload)
            throw new XapiContentException('Malformed signed statement, header serialization issue.');
        
        // Remove JWT data on payload. 
        // xAPI Launch add an 'iat' prop. I don't know if it is normal. But it make the comparison fail.
        unset($payload->iat);

        // Compare statements
        $comparator = new XapiStatementComparator();
        if (!$comparator->compare($statements, $payload))
            throw new XapiContentException('Malformed signed statement, statements do not match.');
    }
    
}
