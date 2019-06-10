<?php
namespace Pingu\Forms\Exceptions;

class FormFieldException extends \Exception{

	public static function alreadyDefined($name)
	{
		return new static("Field $name is already defined in this form");
	}

	public static function notDefined($name)
	{
		return new static("Field $name is not defined in this form");
	}

	public static function notInGroup($name, $group)
	{
		return new static("$name is not a a field in $group");
	}

	public static function missingAttribute($name, $attribute)
	{
		return new static("$name must define a $attribute attribute");
	}

	public static function notAnArray($fieldName, $value)
	{
		return new static("Value for $fieldName must be an array, ".gettype($value)." given");
	}

	public static function notABaseModel($fieldName, $value)
	{
		return new static("Value for $fieldName must be a BaseModel, ".gettype($value)." given");
	}

	public static function cantMoveUp($name)
	{
		return new static("Ca't move $name up, offset too big");
	}

	public static function cantMoveDown($name)
	{
		return new static("Ca't move $name down, offset too big");
	}
}