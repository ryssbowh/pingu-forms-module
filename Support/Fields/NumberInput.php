<?php

namespace Pingu\Forms\Support\Fields;

use Pingu\Forms\Support\Field;

class NumberInput extends Field
{
    /**
     * @inheritDoc
     */
    public function getAttributeOptions(): array
    {
        return array_merge(parent::getAttributeOptions(), ['min', 'max', 'placeholder']);
    }
}