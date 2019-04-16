<?php
namespace Modules\Forms\Renderers;

use FormFacade;

class Select extends InputFieldRenderer
{
	public function renderInput()
	{
		$name = $this->options['name'];
		$this->options['attributes']['id'] = $this->options['name'];
		if($this->options['multiple']){
			$name = $this->options['name'].'[]';
			$this->options['attributes']['multiple'] = true;
		}
		return FormFacade::select($name, $this->options['items'], $this->options['default'] ?? null, $this->options['attributes']);
	}
}