<?php
namespace Pingu\Forms\Exceptions;

use Pingu\Forms\Support\Form;

class GroupException extends \Exception{

    /**
     * @param string $name
     * @param Form   $form
     * 
     * @return GroupException
     */
    public static function alreadyDefined(string $name, Form $form)
    {
        return new static("Group $name is already defined in form ".$form->getName());
    }

    /**
     * @param string $name
     * @param Form   $form
     * 
     * @return GroupException
     */
    public static function notDefined(string $name, Form $form)
    {
        return new static("Group $name is not defined in form ".$form->getName());
    }

    /**
     * @param string $name
     * @param string $group
     * 
     * @return GroupException
     */
    public static function hasField(string $name, string $group)
    {
        return new static("Field $name is already in $group");
    }

}