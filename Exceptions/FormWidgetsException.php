<?php
namespace Pingu\Forms\Exceptions;

class FormWidgetsException extends \Exception
{

    /**
     * @param string $name
     * 
     * @return FormWidgetsException
     */
    public static function noWidgets(string $class)
    {
        return new static("No form widget defined for $class, did you forget to register your field ?");
    }

    /**
     * @param string $name
     * 
     * @return FormWidgetsException
     */
    public static function noFilterWidgets(string $class)
    {
        return new static("No form filter widget defined for $class, did you forget to register your field ?");
    }
}