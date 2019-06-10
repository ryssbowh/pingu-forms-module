<?php
namespace Pingu\Forms\Support\Renderers;

use Pingu\Forms\Contracts\HasItemsContract;

class Checkbox extends Select
{
	public $checkboxes;

	public function __construct(HasItemsContract $field, array $attributes)
	{
		parent::__construct($field, $attributes);
	}
	
	public function getItems()
	{
		$items = $this->field->buildItems();
		foreach($items as $key => $item){
			$attributes = ['id' => $this->field->getName().$key];
			$this->items[$key] = [
				'checked' => in_array($key, $this->getValue() ?? []),
				'attributes' => $attributes,
				'label' => $item
			];
		}
	}

	public function buildForView()
    {
    	return [
    		'name' => $this->name,
    		'items' => $this->getItems(),
    		'attributes' -> $this->attributes->toArray()
    	];
    }
}