<?php
namespace Pingu\Forms\Renderers;

class Radio extends Select
{
	public function buildItems()
	{
		$items = $this->field->buildItems();
		foreach($items as $key => $item){
			$attributes = $this->options['rendererAttributes'];
			$attributes['id'] = $this->options['name'].$key;
			$this->options['radios'][$key] = [
				'checked' => ($this->options['default'] == $key),
				'attributes' => $attributes,
				'label' => $item
			];
		}
	}
}