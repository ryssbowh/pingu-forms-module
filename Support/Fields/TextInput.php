<?php

namespace Pingu\Forms\Support\Fields;

use Pingu\Forms\Support\Field;
use Pingu\Forms\Support\Options\TextInputOptions;

class TextInput extends Field
{
    /**
     * @inheritDoc
     */
    public function getAttributeOptions(): array
    {
        return array_merge(parent::getAttributeOptions(), ['maxLength', 'minLength', 'placeholder']);
    }

    /**
     * @inheritDoc
     */
    public static function defaultOptions(): array
    {
        return array_merge(Field::defaultOptions(),[
            'maxLength' => 255
        ]);
    }

    /**
     * @inheritDoc
     */
    public static function options(): string
    {
        return TextInputOptions::class;
    }
}