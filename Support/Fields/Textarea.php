<?php
namespace Pingu\Forms\Support\Fields;

use Pingu\Forms\Support\Options\TextareaOptions;

class Textarea extends TextInput
{
    /**
     * @inheritDoc
     */
    public static function options(): string
    {
        return TextareaOptions::class;
    }

    public static function defaultOptions(): array
    {
        return [
            'rows' => 5
        ];
    }
}