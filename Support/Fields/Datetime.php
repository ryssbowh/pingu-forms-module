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

    /**
     * @inheritDoc
     */
    protected function getDefaultClasses(): array
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