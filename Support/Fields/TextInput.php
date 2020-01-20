<?php

namespace Pingu\Forms\Support\Fields;

use Pingu\Forms\Support\Field;
use Pingu\Forms\Support\Options\TextInputOptions;

class TextInput extends Field
{
    /**
     * @inheritDoc
     */
    protected $attributeOptions = ['required', 'disabled', 'maxLength', 'minLength', 'placeholder', 'id'];

    /**
     * @inheritDoc
     */
    public static function defaultOptions(): array
    {
        return [
            'maxLength' => 255
        ];
    }

    /**
     * @inheritDoc
     */
    public static function options(): string
    {
        return TextInputOptions::class;
    }
}