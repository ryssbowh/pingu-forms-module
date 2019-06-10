<?php
namespace Pingu\Forms\Exceptions;

class FormFieldModelException extends \Exception{

	public static function notDefined($name, $model)
	{
		return new static("Field $name is not defined in ".get_class($model));
	}
}