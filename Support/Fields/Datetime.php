<?php
namespace Pingu\Forms\Support\Fields;

use Pingu\Forms\Support\Field;

class Datetime extends Field
{
    /**
     * @inheritDoc
     */   
    public function __construct(string $name, array $options = [], array $attributes = [])
    {
        $options['format'] = $options['format'] ?? 'YYYY-MM-DD HH:mm:ss';
        parent::__construct($name, $options, $attributes);
    }

}