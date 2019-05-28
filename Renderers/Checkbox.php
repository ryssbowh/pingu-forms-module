<?php
namespace Pingu\Forms\Renderers;

use Pingu\Forms\Renderers\Radio;

class Checkbox extends Select
{
	public function buildItems()
	{
		$items = $this->field->buildItems();
		foreach($items as $key => $item){
			$attributes = $this->options['rendererAttributes'];
			$attributes['id'] = $this->options['name'].$key;
			$this->options['checkboxes'][$key] = [
				'checked' => in_array($key, $this->options['default']),
				'attributes' => $attributes,
				'label' => $item
			];
		}
		if($this->options['multiple']){
			$this->options['name'] .= '[]';
		}
	}
}