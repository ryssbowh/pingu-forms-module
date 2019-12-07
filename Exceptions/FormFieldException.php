<?php
namespace Pingu\Forms\Exceptions;

use Pingu\Field\Contracts\HasFields;
use Pingu\Forms\Support\Form;

class FormFieldException extends \Exception
{
    /**
     * @param string $name
     * @param Form   $form
     * 
     * @return FormFieldException
     */
    public static function alreadyDefined(string $name, Form $form)
    {
        return new static("Field '$name' is already defined in form ".$form->getName());
    }

    /**
     * @param string $name
     * @param Form   $form
     * 
     * @return FormFieldException
     */
    public static function notDefined(string $name, Form $form)
    {
        return new static("Field '$name' is not defined in form ".$form->getName());
    }

    /**
     * @param string $name
     * @param string $option
     * 
     * @return FormFieldException
     */
    public static function missingOption(string $name, string $option)
    {
        return new static("Field '$name' is missing a '$option' option'");
    }

    /**
     * @param string $fieldName
     * @param string $name
     * @param string $model
     * 
     * @return FormFieldException
     */
    public static function modelFieldDoesntExist(string $fieldName, string $name, string $model)
    {
        return new static("Field '$fieldName' : field '$name' is not defined in model $model");
    }
}