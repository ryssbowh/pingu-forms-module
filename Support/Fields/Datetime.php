<?php
namespace Pingu\Forms\Support\Fields;

use Pingu\Forms\Support\Field;

class Datetime extends Field
{
    /**
     * @var array
     */
    protected $requiredOptions = ['format'];

    protected function init(array $options)
    {
        $options['format'] = convertPhpToJsMomentFormat($options['format']);
        parent::init($options);
    }

    protected function getDefaultClasses()
    {
        return [
            'field',
            'field-'.$this->name,
            'field-'.$this->getType(),
            'form-control', 
            'datetimepicker-input'
        ];
    }
}