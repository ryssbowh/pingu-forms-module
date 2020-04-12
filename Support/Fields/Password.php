<?php

namespace Pingu\Forms\Support\Fields;

use Pingu\Forms\Support\Field;

class Password extends Field
{
    /**
     * @inheritDoc
     */
    public function getAttributeOptions(): array
    {
        return array_merge(parent::getAttributeOptions(), ['maxLength', 'minLength']);
    }
}