<?php
namespace Pingu\Forms\Support\Fields;

use Pingu\Forms\Support\Field;

class Submit extends Field
{
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