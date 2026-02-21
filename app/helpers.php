<?php

/**
 * Dolibarr Helper Functions
 * 
 * These functions are extracted from Dolibarr to allow gradual refactoring
 * from legacy Dolibarr code to modern Laravel code.
 */

if (!function_exists('GETPOST')) {
    /**
     * Get a parameter from POST or GET request
     * 
     * @param string $paramname Name of parameter
     * @param string $check Type of check ('alpha', 'aZ09', 'int', 'intcomma', 'alphanohtml', 'array', etc.)
     * @param int $method 1=GET, 2=POST, 3=POST then GET, 0=GET then POST (default)
     * @param int $filter Filter to apply (not implemented yet)
     * @param mixed $options Additional options
     * @param mixed $noreplace No replacement
     * @return mixed The parameter value
     */
    function GETPOST($paramname, $check = 'alphanohtml', $method = 0, $filter = null, $options = null, $noreplace = 0)
    {
        $request = request();
        
        // Determine method order
        if ($method == 1) {
            $value = $request->query($paramname);
        } elseif ($method == 2) {
            $value = $request->post($paramname);
        } elseif ($method == 3) {
            $value = $request->post($paramname, $request->query($paramname));
        } else {
            $value = $request->input($paramname);
        }
        
        // Handle arrays
        if ($check === 'array' || $check === 'array:int') {
            if (!is_array($value)) {
                $value = [];
            }
            if ($check === 'array:int') {
                $value = array_map('intval', $value);
            }
            return $value;
        }
        
        // If no value found, return appropriate default
        if ($value === null) {
            if ($check === 'int' || $check === 'intcomma') {
                return 0;
            }
            return '';
        }
        
        // Apply checks/filters
        switch ($check) {
            case 'int':
                return (int) $value;
            case 'intcomma':
                // Remove all non-digit characters except comma
                return preg_replace('/[^0-9,]/', '', $value);
            case 'alpha':
                // Only letters
                return preg_replace('/[^a-zA-Z]/', '', $value);
            case 'aZ09':
            case 'aZ':
                // Letters and numbers
                return preg_replace('/[^a-zA-Z0-9]/', '', $value);
            case 'alphanohtml':
                // Strip HTML tags
                return strip_tags($value);
            default:
                return $value;
        }
    }
}

if (!function_exists('GETPOSTINT')) {
    /**
     * Get an integer parameter from POST or GET request
     * 
     * @param string $paramname Name of parameter
     * @param int $method 1=GET, 2=POST, 3=POST then GET, 0=GET then POST (default)
     * @return int The parameter value as integer
     */
    function GETPOSTINT($paramname, $method = 0)
    {
        return (int) GETPOST($paramname, 'int', $method);
    }
}

if (!function_exists('GETPOSTISSET')) {
    /**
     * Check if a parameter exists in POST or GET request
     * 
     * @param string $paramname Name of parameter
     * @return bool True if parameter is set
     */
    function GETPOSTISSET($paramname)
    {
        return request()->has($paramname);
    }
}

if (!function_exists('dol_print_date')) {
    /**
     * Format a date for display
     * 
     * @param mixed $time Timestamp or date string
     * @param string $format Format type ('day', 'dayhour', 'hour', etc.)
     * @param string $tzoutput Timezone output
     * @param object $langs Language object (optional)
     * @param bool $morethanoneday Show more than one day
     * @return string Formatted date
     */
    function dol_print_date($time, $format = '', $tzoutput = 'auto', $langs = null, $morethanoneday = false)
    {
        if (empty($time)) {
            return '';
        }
        
        // Convert to Carbon instance if needed
        if (is_numeric($time)) {
            $date = \Carbon\Carbon::createFromTimestamp($time);
        } elseif (is_string($time)) {
            $date = \Carbon\Carbon::parse($time);
        } elseif ($time instanceof \Carbon\Carbon) {
            $date = $time;
        } else {
            return '';
        }
        
        // Apply format
        switch ($format) {
            case 'day':
                return $date->format('Y-m-d');
            case 'dayhour':
                return $date->format('Y-m-d H:i');
            case 'hour':
                return $date->format('H:i');
            case 'daytext':
                return $date->format('F j, Y');
            default:
                return $date->format('Y-m-d H:i:s');
        }
    }
}

if (!function_exists('price')) {
    /**
     * Format a price for display
     * 
     * @param mixed $amount Amount to format
     * @param int $form Form type
     * @param object $outlangs Language object (optional)
     * @param int $trunc Truncate decimals
     * @param int $rounding Rounding precision
     * @param int $forcerounding Force rounding
     * @param string $currency Currency symbol
     * @return string Formatted price
     */
    function price($amount, $form = 0, $outlangs = null, $trunc = 1, $rounding = -1, $forcerounding = -1, $currency = '')
    {
        if ($amount === null || $amount === '') {
            return '';
        }
        
        $amount = floatval($amount);
        
        // Default formatting
        $decimals = 2;
        if ($rounding >= 0) {
            $decimals = $rounding;
        }
        
        $formatted = number_format($amount, $decimals, '.', ' ');
        
        if ($currency) {
            $formatted .= ' ' . $currency;
        }
        
        return $formatted;
    }
}

if (!function_exists('img_picto')) {
    /**
     * Show a picto image
     * 
     * @param string $titlealt Alt and title text
     * @param string $picto Picto name
     * @param string $moreatt More attributes
     * @param bool $pictoisfullpath Is picto a full path
     * @param int $srconly Return only src
     * @param int $notitle No title
     * @param string $alt Alt text
     * @param string $morecss More CSS classes
     * @param int $marginleftonlyshort Margin left only if short
     * @return string HTML img tag
     */
    function img_picto($titlealt = '', $picto = 'generic', $moreatt = '', $pictoisfullpath = 0, $srconly = 0, $notitle = 0, $alt = '', $morecss = '', $marginleftonlyshort = 0)
    {
        // Simple implementation - can be enhanced
        $title = $notitle ? '' : ' title="' . htmlspecialchars($titlealt) . '"';
        $alt = $alt ?: $titlealt;
        $class = $morecss ? ' class="' . htmlspecialchars($morecss) . '"' : '';
        
        if ($srconly) {
            return '';
        }
        
        return '<i' . $class . $title . ' data-picto="' . htmlspecialchars($picto) . '"></i>';
    }
}

if (!function_exists('getDolGlobalString')) {
    /**
     * Get a global configuration string
     * 
     * @param string $key Configuration key
     * @param string $default Default value
     * @return string Configuration value
     */
    function getDolGlobalString($key, $default = '')
    {
        return config('dolibarr.' . $key, $default);
    }
}

if (!function_exists('getDolGlobalInt')) {
    /**
     * Get a global configuration integer
     * 
     * @param string $key Configuration key
     * @param int $default Default value
     * @return int Configuration value
     */
    function getDolGlobalInt($key, $default = 0)
    {
        return (int) config('dolibarr.' . $key, $default);
    }
}

if (!function_exists('isModEnabled')) {
    /**
     * Check if a module is enabled
     * 
     * @param string $module Module name
     * @return bool True if enabled
     */
    function isModEnabled($module)
    {
        return config('dolibarr.modules.' . $module, false);
    }
}

if (!function_exists('newToken')) {
    /**
     * Generate a CSRF token
     * 
     * @return string CSRF token
     */
    function newToken()
    {
        return csrf_token();
    }
}
