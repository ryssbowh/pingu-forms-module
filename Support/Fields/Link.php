<?php
namespace Pingu\Forms\Support\Fields;

use Pingu\Forms\Support\Field;

class Link extends Field
{
    /**
     * @inheritDoc
     */
    protected $requiredOptions = ['label', 'url'];
}