<?php

namespace Pingu\Forms\Support\Fields;

use Pingu\Forms\Support\Field;

class NumberInput extends Field
{
    /**
     * @inheritDoc
     */
    protected $attributeOptions = ['required', 'min', 'max']; 
}