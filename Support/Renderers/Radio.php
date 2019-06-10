<?php
namespace Pingu\Forms\Renderers;

use Pingu\Forms\Contracts\HasItemsContract;

class Radio extends Select
{
	public function getItems()
	{
		$items = $this->field->buildItems();
		foreach($items as $key => $item){
			$attributes = ['id', $this->field->getName().$key];
			$this->items[$key] = [
				'checked' => in_array($key, $this->getValue() ?? []),
				'attributes' => $attributes,
				'label' => $item
			];
		}
		return $items;
	}
}