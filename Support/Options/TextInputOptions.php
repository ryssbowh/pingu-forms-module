<?php 

namespace Pingu\Forms\Support\Options;

use Pingu\Forms\Support\FieldOptions;
use Pingu\Forms\Support\Fields\NumberInput;
use Pingu\Forms\Support\Fields\TextInput;

class TextInputOptions extends FieldOptions
{
    /**
     * @inheritDoc
     */
    protected $optionNames = ['maxLength'];

    /**
     * @inheritDoc
     */
    protected $labels = [
        'maxLength' => 'Size'
    ];

    /**
     * @inheritDoc
     */
    protected $formFieldClass = TextInput::class;

    /**
     * @inheritDoc
     */
    public function getValidationRules(): array
    {
        return [
            'maxLength' => 'integer|min:0'
        ];
    }

    /**
     * @inheritDoc
     */
    public function toFormElements(): array
    {
        return [
            new NumberInput(
                'maxLength',
                [
                    'default' => $this->value('maxLength'),
                    'label' => $this->label('maxLength')
                ]
            )
        ];
    }
}