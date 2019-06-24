<?php
namespace Pingu\Forms\Exceptions;

use Pingu\Forms\Support\Form;

class FormFieldException extends \Exception{

	public static function alreadyDefined($name, Form $form)
	{
		return new static("Field '$name' is already defined in form ".$form->getName());
	}

	public static function notDefined($name)
	{
		return new static("Field '$name' is not defined in this form");
	}

	public static function notInGroup($name, $group)
	{
		return new static("'$name' is not a a field in $group");
	}

	public static function missingAttribute($name, $attribute)
	{
		return new static("Field '$name' must define a '$attribute' attribute");
	}

	public static function missingDefinition($name, $attribute)
	{
		return new static("definition of field '$name' must define '$attribute'");
	}

	public static function notAnArray(string $fieldName, $value)
	{
		return new static("Value for $fieldName must be an array, ".gettype($value)." given");
	}

	public static function notABaseModel(string $fieldName, $value)
	{
		return new static("Value for $fieldName must be a BaseModel, ".gettype($value)." given");
	}

	public static function cantMoveUp(string $name)
	{
		return new static("Ca't move $name up, offset too big");
	}

	public static function cantMoveDown(string $name)
	{
		return new static("Ca't move $name down, offset too big");
	}

	public static function notDefinedInModel(string $name, string $model)
	{
		return new static("Field $name is not defined in ".$model);
	}

	public static function missingOption($name, $option)
	{
		return new static("Field '$name' is missing a '$option' option'");
	}
}