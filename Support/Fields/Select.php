<?php
namespace Pingu\Forms\Support\Fields;

use Illuminate\Support\Arr;
use Pingu\Forms\Support\Field;
use Pingu\Forms\Support\ItemList;

class Select extends Field
{
    protected $required = ['items'];
    protected $items;
    protected $value = [];

    public function __construct(string $name, array $options = [], array $attributes = [])
    {   
        parent::__construct($name, $options, $attributes);
        $this->attribute('multiple', $this->isMultiple());
        $this->items = $this->buildItems();
    }

    public function getHtmlName()
    {
        return $this->name . ($this->isMultiple() ? '[]' : '');
    }

    /**
     * @inheritDoc
     */
    public function setValue($value)
    {
        if ($value) {
            $this->value = array_map('strval', Arr::wrap($value));
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function buildItems()
    {
        return new ItemList($this->option('items'), $this->getName());
    }
    
    /**
     * @inheritDoc
     */
    public function getItems(): ItemList
    {
        return $this->items;
    }

    public function getViewData()
    {
        $array = array_merge(
            parent::getViewData(),
            ['items' => $this->items->toArray()]
        );
        return $array;
    }
}