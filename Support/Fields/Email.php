<?php

namespace Pingu\Forms\Support\Fields;

use Pingu\Forms\Support\Field;

class Email extends Field
{
    protected $attributeOptions = ['required', 'maxlength', 'minlength']; 
}