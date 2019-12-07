<?php
namespace Pingu\Forms\Support\Fields;

use Pingu\Forms\Support\Field;

class Submit extends Field
{
    /**
     * @inheritDoc
     */
    public function __construct(string $name = '_submit', array $options = [], array $attributes = [])
    {
        $options['label'] = $options['label'] ?? 'Submit';
        parent::__construct($name, $options, $attributes);
    }
}