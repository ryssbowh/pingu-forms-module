<?php
namespace Pingu\Forms\Exceptions;

use Pingu\Forms\Support\Form;

class GroupException extends \Exception{

    public static function alreadyDefined($name, Form $form)
    {
        return new static("Group $name is already defined in form ".$form->getName());
    }

    public static function notDefined($name, Form $form)
    {
        return new static("Group $name is not defined in form ".$form->getName());
    }

    public static function hasField($name, $group)
    {
        return new static("Field $name is already in $group");
    }

}