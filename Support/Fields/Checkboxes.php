<?php
namespace Pingu\Forms\Support\Fields;

use Pingu\Forms\Support\Field;
use Pingu\Forms\Support\ItemList;

class Checkboxes extends Select
{
    /**
     * Builds the item list
     * 
     * @return ItemList
     */
    public function buildItems()
    {
        $items = $this->option('items');
        if (isset($items[''])) {
            unset($items['']);
        }
        return new ItemList($items, $this->getName());
    }
}