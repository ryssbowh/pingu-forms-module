<?php 

namespace Pingu\Forms\Support;

use Illuminate\Support\Collection;

class ItemList
{
	protected $items;

	public function __construct(array $list, string $fieldName = null)
	{	
		$this->items = collect();

		foreach($list as $key => $value){
			$item = new Item($key, $value);
			if($fieldName){
				$item->setId($fieldName);
			}
			$this->items->put($key, $item);
		}
	}

	/**
	 * Attribute setter
	 * 
	 * @param  string $name
	 * @param  mixed $value
	 * @return ItemList
	 */
	public function attribute(string $name, $value)
	{
		foreach($this->items as $item){
			$item->attribute($name, $value);
		}
		return $this;
	}

	/**
	 * Is this empty of items 
	 * 
	 * @return bool
	 */
	public function empty()
	{
		return sizeof($this->items) == 0;
	}

	/**
	 * Does this have an item
	 * 
	 * @param  string  $key
	 * @return boolean
	 */
	public function hasItem(string $key)
	{
		return $this->items->has($key);
	}

	/**
	 * get an item by key 
	 * 
	 * @param  string $key
	 * @return Item
	 */
	public function getItem(string $key)
	{
		if($this->hasItem($key)){
			return $this->items->get($key);
		}
		return null;
	}

	/**
	 * Gets all items
	 * 
	 * @return Collection
	 */
	public function getItems(){
		return $this->items;
	}

}