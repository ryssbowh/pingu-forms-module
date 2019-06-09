<?php
namespace Pingu\Forms\Exceptions;

class FormActionException extends \Exception{

	public static function alreadyDefined($name)
	{
		return new static("Action $name is already defined in this form");
	}

	public static function labelNotDefined($name)
	{
		return new static("Label for action $name is not defined");
	}

	public static function notDefined($name)
	{
		return new static("Action $name is not defined in this form");
	}

	public static function typeNotDefined($name)
	{
		return new static("Type for action $name is not defined, possible values are 'submit' or 'button'");
	}
}