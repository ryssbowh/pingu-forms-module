<?php 

namespace Pingu\Forms\Support\Options;

use Pingu\Forms\Support\FieldOptions;
use Pingu\Forms\Support\Fields\NumberInput;
use Pingu\Forms\Support\Fields\Textarea;

class TextareaOptions extends FieldOptions
{
    /**
     * @inheritDoc
     */
    protected $optionNames = ['rows'];

    /**
     * @inheritDoc
     */
    protected $labels = [
        'rows' => 'Rows'
    ];

    /**
     * @inheritDoc
     */
    protected $formFieldClass = Textarea::class;

    /**
     * @inheritDoc
     */
    public function getValidationRules(): array
    {
        return [
            'rows' => 'integer|min:1'
        ];
    }

    /**
     * @inheritDoc
     */
    public function toFormElements(): array
    {
        return [
            new NumberInput(
                'rows',
                [
                    'default' => $this->value('rows'),
                    'label' => $this->label('rows')
                ]
            )
        ];
    }
}