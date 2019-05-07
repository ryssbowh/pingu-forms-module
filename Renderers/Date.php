<?php
namespace Pingu\Forms\Renderers;

use FormFacade;

class Date extends InputFieldRenderer
{
	public function renderInput()
	{
		return FormFacade::date($this->options['name'], $this->options['attributes']);
	}
}