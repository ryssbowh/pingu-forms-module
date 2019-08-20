<?php

use Illuminate\Support\Str;


/**
 * Turn a machine name into a label
 * example : 'machineName' into 'Machine name'
 * 
 * @param  string $name
 * @return string
 */
function label(string $name)
{
	return ucfirst(str_replace('-', ' ', strtolower(Str::kebab($name))));
}

function removeUnderscoreKeys(array $array)
{
	return array_filter($array, function($key){
		return substr($key, 0, 1) != '_';
	}, ARRAY_FILTER_USE_KEY);
}