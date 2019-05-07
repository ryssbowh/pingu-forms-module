<?php
namespace Pingu\Forms\Renderers;

use FormFacade;

class Number extends InputFieldRenderer
{
	public function renderInput()
	{
		return FormFacade::number($this->options['name'], $this->options['default'], $this->options['attributes']);
	}
}