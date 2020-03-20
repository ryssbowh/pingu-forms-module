<?php
namespace Pingu\Forms\Support\Fields;

use Illuminate\Support\Arr;
use Pingu\Forms\Support\Field;
use Pingu\Forms\Support\ItemList;

class Select extends Field
{
    /**
     * @inheritDoc
     */
    protected $requiredOptions = ['items'];

    /**
     * @var ItemList
     */
    protected $items;

    /**
     * @var array
     */
    protected $value = [];

    /**
     * @inheritDoc
     */
    protected $attributeOptions = ['data-placeholder', 'required', 'disabled', 'id', 'placeholder', 'multiple']; 

    protected function init(array $options)
    {
        parent::init($options);
        $this->items = $this->buildItems();
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
     * Builds the item list
     * 
     * @return ItemList
     */
    public function buildItems()
    {
        return new ItemList($this->option('items'), $this->getName());
    }
    
    /**
     * Items getter
     * 
     * @return ItemList
     */
    public function getItems(): ItemList
    {
        return $this->items;
    }

    /**
     * @inheritDoc
     */
    public function getViewData(): array
    {
        $array = array_merge(
            parent::getViewData(),
            ['items' => $this->items->toArray()]
        );
        return $array;
    }
}