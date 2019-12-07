<?php
namespace Pingu\Forms\Exceptions;

class FormException extends \Exception
{

    /**
     * @param string $name
     * 
     * @return FormException
     */
    public static function name(string $name)
    {
        return new static("form name '$name' is not valid, a form name can only contain letters, numbers and hyphens");
    }

    /**
     * @param string $name
     * 
     * @return FormException
     */
    public static function notBuilt(string $name)
    {
        return new static('form '.$name." is not finished building, call '\$form->built()' before rendering it.");
    }

    /**
     * @param string $name
     * 
     * @return FormException
     */
    public static function noGroups(string $name)
    {
        return new static('form '.$name." doesn't define any group.");
    }
}