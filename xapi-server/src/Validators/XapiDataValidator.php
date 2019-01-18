<?php

namespace Trax\XapiServer\Validators;

use Trax\XapiServer\XapiStatementValidator;

class XapiDataValidator
{
    /**
     * App.
     */
    protected $app;
    
    /**
     * Validator.
     */
    protected $validator;
    
    /**
     * Statement validator.
     */
    protected $statement;

    
    /**
     * Construct.
     */
    public function __construct($app)
    {
        $this->app = $app;
        $this->validator = $app->make('validator');
        $this->statement = new XapiStatementValidator();
    }
    
    /**
     * Register the rules.
     */
    public function register()
    {
        $this->registerXapiFormatRule();
        $this->registerXapiAgentRule();
        $this->registerXapiVersionRule();
        $this->registerXapiMboxRule();
        $this->registerXapiLangMapRule();
        $this->registerXapiScaledRule();
    }
    
    /**
     * Register xAPI Format rule.
     */
    protected function registerXapiFormatRule()
    {
        $this->validator->extend('xapi_format', function ($attribute, $value, $parameters, $validator) {
            if (!is_string($value)) return false;
            return in_array($value, ['ids', 'exact', 'canonical']);
        });
    }

    /**
     * Register xAPI Agent rule.
     */
    protected function registerXapiAgentRule()
    {
        $this->validator->extend('xapi_agent', function ($attribute, $value, $parameters, $validator) {
            if (is_string($value)) $value = json_decode($value);
            if (!$value) return false;
            try {
                $this->statement->validateAgent($value);
                return true;
            } catch(\Exception $e) {
                return false;
            }
        });
    }

    /**
     * Register xAPI Version rule.
     */
    protected function registerXapiVersionRule()
    {
        $this->validator->extend('xapi_version', function ($attribute, $value, $parameters, $validator) {
            if (!is_string($value)) return false;
            $version = explode('.', $value);
            if (count($version) < 2 || count($version) > 3 || $version[0] !== '1' || $version[1] !== '0') return false;
            return true;
        });
    }

    /**
     * Register xAPI Mailto rule.
     */
    protected function registerXapiMboxRule()
    {
        $this->validator->extend('xapi_mbox', function ($attribute, $value, $parameters, $validator) {
            if (!is_string($value)) return false;
            $parts = explode(':', $value);
            return (count($parts) == 2 && $parts[0] == 'mailto' && traxValidate($parts[1], 'email'));
        });
    }

    /**
     * Register xAPI Lang Map rule.
     */
    protected function registerXapiLangMapRule()
    {
        $this->validator->extend('xapi_lang_map', function ($attribute, $value, $parameters, $validator) {
            if (is_string($value)) $value = json_decode($value);
            if (!$value || !is_object($value)) return false;
            $langs = get_object_vars($value);
            foreach($langs as $lang => $string) {
                if (!is_string($string)) return false;
                if (!traxValidate($lang, 'iso_lang')) return false;
            }
            return true;
        });
    }

    /**
     * Register xAPI Scaled rule.
     */
    protected function registerXapiScaledRule()
    {
        $this->validator->extend('xapi_scaled', function ($attribute, $value, $parameters, $validator) {
            return (!is_string($value) && is_numeric($value) && $value <= 1 && $value >= -1);
        });
    }

}
