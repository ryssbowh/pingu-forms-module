<?php

use Illuminate\Support\Str;

if (! function_exists('form_label')) {
    /**
     * Turn a machine name into a label
     * example : 'machineName' into 'Machine name'
     * 
     * @param string $name
     * 
     * @return string
     */
    function form_label(string $name)
    {
        return ucfirst(str_replace('-', ' ', strtolower(Str::kebab($name))));
    }
}

if (! function_exists('removeUnderscoreKeys')) {
    /**
     * Removes all the keys that start with underscore from an array
     * 
     * @param array  $array
     * 
     * @return array
     */
    function removeUnderscoreKeys(array $array)
    {
        return array_filter(
            $array, function ($key) {
                return substr($key, 0, 1) != '_';
            }, ARRAY_FILTER_USE_KEY
        );
    }
}