<?php
namespace Pingu\Forms\Support\Fields;

use Pingu\Forms\Support\Field;

class Submit extends Field
{
    public function __construct(string $name = '_submit', array $options = [])
    {
        parent::__construct($name, $options);
    }
    /**
     * @inheritDoc
     */
    protected function getDefaultOptions(): array
    {
        return [
            'showLabel' => false,
            'label' => 'Submit'
        ];
    }
}