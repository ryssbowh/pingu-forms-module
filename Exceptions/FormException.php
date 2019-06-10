<?php
namespace Pingu\Forms\Exceptions;

class FormException extends \Exception{

	public static function name($name)
	{
		return new static("'$name' is not valid, a form name can only contain letters, underscores and hyphens");
	}

	public static function notBuilt($name)
	{
		return new static($name." is not finished building, call '\$form->built()' before rendering it.");
	}
}