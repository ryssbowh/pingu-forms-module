<?php 

namespace Pingu\Forms\Support\Options;

use Pingu\Forms\Support\FieldOptions;
use Pingu\Forms\Support\Fields\Checkbox;

class CheckboxOptions extends FieldOptions
{
    /**
     * @inheritDoc
     */
    protected $optionNames = ['useLabel'];

    /**
     * @inheritDoc
     */
    protected $labels = [
        'useLabel' => 'Use field label'
    ];

    /**
     * @inheritDoc
     */
    protected $casts = [
        'useLabel' => 'bool'
    ];

    /**
     * @inheritDoc
     */
    protected $formFieldClass = Checkbox::class;

    /**
     * @inheritDoc
     */
    public function getValidationRules(): array
    {
        return [
            'useLabel' => 'boolean'
        ];
    }

    /**
     * @inheritDoc
     */
    public function friendlyValue(string $name)
    {
        if ($name == 'useLabel') {
            return $this->values[$name] ? 'Yes' : 'No';
        }
        return $this->values[$name] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function toFormElements(): array
    {
        return [
            new Checkbox(
                'useLabel',
                [
                    'default' => $this->value('useLabel'),
                    'label' => $this->label('useLabel'),
                    'useLabel' => false
                ]
            )
        ];
    }
}