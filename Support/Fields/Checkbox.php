<?php
namespace Pingu\Forms\Support\Fields;

use Pingu\Forms\Support\Field;
use Pingu\Forms\Support\Options\CheckboxOptions;

class Checkbox extends Field
{
    /**
     * @inheritDoc
     */
    public static function options(): string
    {
        return CheckboxOptions::class;
    }

    public static function defaultOptions(): array
    {
        return array_merge(Field::defaultOptions(), [
            'useLabel' => false
        ]);
    }
}