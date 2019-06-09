<?php
namespace Pingu\Forms\Exceptions;

class FormFieldException extends \Exception{

	public static function alreadyDefined($name)
	{
		parent::__construct("Field $name is already defined in this form");
	}

	public static function notDefined($name)
	{
		parent::__construct("Field $name is not defined in this form");
	}

	public static function notInGroup($name, $group)
	{
		parent::__construct("$name is not a a field in $group");
	}

	public static function missingAttribute($name, $attribute)
	{
		parent::__construct("$name must define a $attribute attribute");
	}
}