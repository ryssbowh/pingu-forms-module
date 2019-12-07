<?php

namespace Pingu\Forms\Support\Fields;

use Pingu\Forms\Support\Field;

class Password extends Field
{
    /**
     * @inheritDoc
     */
    protected $attributeOptions = ['required', 'maxlength', 'minlength']; 
}