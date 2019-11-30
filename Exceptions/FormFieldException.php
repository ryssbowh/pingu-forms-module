<?php
namespace Pingu\Forms\Exceptions;

use Pingu\Field\Contracts\HasFields;
use Pingu\Forms\Support\Form;

class FormFieldException extends \Exception
{
    public static function alreadyDefined($name, Form $form)
    {
        return new static("Field '$name' is already defined in form ".$form->getName());
    }

    public static function notDefined($name, Form $form)
    {
        return new static("Field '$name' is not defined in form ".$form->getName());
    }

    public static function missingOption($name, $option)
    {
        return new static("Field '$name' is missing a '$option' option'");
    }

    public static function modelFieldDoesntExist($fieldName, $name, $model)
    {
        return new static("Field '$fieldName' : field '$name' is not defined in model $model");
    }
}