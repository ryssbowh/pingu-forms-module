<?php 

namespace Pingu\Forms\Support;

use Illuminate\Support\Collection;

class ItemList
{
    protected $items;

    public function __construct(array $list, string $fieldName = null)
    {   
        $this->items = collect();

        $this->addItems($list, $fieldName);
    }

    /**
     * Attribute setter
     * 
     * @param string $name
     * @param mixed  $value
     * 
     * @return ItemList
     */
    public function attribute(string $name, $value): ItemList
    {
        foreach ($this->items as $item) {
            $item->attribute($name, $value);
        }
        return $this;
    }

    /**
     * Is this empty of items 
     * 
     * @return bool
     */
    public function empty(): bool
    {
        return sizeof($this->items) == 0;
    }

    /**
     * Does this have an item
     * 
     * @param string $key
     * 
     * @return boolean
     */
    public function hasItem(string $key): bool
    {
        return $this->items->has($key);
    }

    /**
     * get an item by key 
     * 
     * @param string $key
     * 
     * @return Item
     */
    public function getItem(string $key): Item
    {
        if ($this->hasItem($key)) {
            return $this->items->get($key);
        }
        return null;
    }

    /**
     * Gets all items
     * 
     * @return Collection
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    /**
     * Add one item to the list
     * 
     * @param mixed       $key
     * @param string      $label
     * @param string|null $id
     *
     * @return ItemList
     */
    public function addItem($key, string $label, string $id = null): ItemList
    {
        $this->items->put($key, new Item($key, $label, $id));
        return $this;
    }

    /**
     * Add items to the list
     * 
     * @param array  $items
     * @param string $id
     *
     * @return ItemList
     */
    public function addItems(array $items, string $id): ItemList
    {
        foreach ($items as $key => $label) {
            $this->addItem($key, $label, $id);
        }
        return $this;
    }

    /**
     * Renders to array
     * 
     * @return array
     */
    public function toArray(): array
    {
        $out = [];
        foreach ($this->items as $item) {
            $out[$item->getKey()] = $item->getLabel();
        }
        return $out;
    }

}