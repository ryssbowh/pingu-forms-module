<?php
namespace Pingu\Forms\Exceptions;

class FormWidgetsException extends \Exception
{

    /**
     * @param string $name
     * 
     * @return FormException
     */
    public static function nothingAvailable(string $class)
    {
        return new static("No form widget defined for $class, did you forget to register your field ?");
    }
}