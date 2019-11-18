<?php
namespace Pingu\Forms\Support\Fields;

use Pingu\Forms\Support\Field;
use Pingu\Forms\Support\ItemList;

class Checkboxes extends Select
{
    /**
     * @inheritDoc
     */
    public function isMultiple(): bool
    {
        return true;
    }
}