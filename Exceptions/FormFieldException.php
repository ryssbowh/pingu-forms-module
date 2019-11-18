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

    public static function notDefined($name)
    {
        return new static("Field '$name' is not defined in this form");
    }

    public static function missingOption($name, $option)
    {
        return new static("Field '$name' is missing a '$option' option'");
    }

}