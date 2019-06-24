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