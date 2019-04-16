<?php
namespace Modules\Forms\Renderers;

use Collective\Html\FormFacade;
use Modules\Forms\Renderers\Radio;

class Checkbox extends Radio
{
	public function buildItems()
	{
		$name = $this->options['multiple'] ? $this->options['name'].'[]' : $this->options['name'];
		foreach($this->options['items'] as $key => $item){
			$attributes = $this->options['attributes'];
			$attributes['id'] = $this->options['name'].$key;
			$this->options['checkboxes'][$key] = FormFacade::checkbox($name, $key, in_array($key, array_keys($this->options['default'])), $attributes);
		}
	}
}