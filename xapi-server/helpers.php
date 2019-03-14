<?php

/**
 * Evaluate a boolean var in a flexible manner ("false" is evaluated false).
 */
if (! function_exists('traxBool')) {
    
    function traxBool($var) {
        return boolval($var) && $var !== 'false';
    }
}

/**
 * Get ISO8601 timestamp with milliseconds and Z (UTC).
 */
if (! function_exists('traxIsoTimestamp')) {
    
    function traxIsoTimestamp($isoTimestamp = null) {
        if (!isset($isoTimestamp)) {
            
            // Now
            try {
                list($seconds, $micro) = explode('.', microtime(true));
            } catch(\Exception $e) {
                // $micro may be missing (in case of N.000000)
                $micro = '0001';
            }
            if (strlen($micro) < 4) $micro = substr($micro.'000', 0, 3).'1';
            if (substr($micro, 2, 2) == '00') $micro = substr($micro, 0, 2).'01'; 
            $res = date('Y-m-d\TH:i:s.', $seconds) . $micro . 'Z';
            return $res;
        
        } else {
            
            // Convert
            $timestamp = date('Y-m-d\TH:i:s.', strtotime($isoTimestamp));
            
            // Remove timezone
            list($date, $time) = explode('T', $isoTimestamp);
            if (strpos($time, '+') !== false) list($time, $forget) = explode('+', $time);
            else if (strpos($time, '-') !== false) list($time, $forget) = explode('-', $time);
            else if (strpos($time, 'Z') !== false) list($time) = explode('Z', $time);
            
            // Micro
            if (strpos($time, '.') !== false) {
                list($time, $micro) = explode('.', $time);
                $micro = substr($micro, 0, 4);
                if (strlen($micro) < 4) $micro = substr($micro.'0000', 0, 4);
            } else {
                $micro = '0000';
            }
            $res = $timestamp.$micro.'Z';
            return $res;
        }
    }
}

