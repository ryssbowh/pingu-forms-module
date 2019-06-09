<?php
namespace Pingu\Forms\Exceptions;

class FormFieldModelException extends \Exception{

	public static function notDefined($name, $model)
	{
		parent::__construct("Field $name is not defined in ".get_class($model));
	}
}