<?php
namespace Pingu\Forms\Renderers;

use FormFacade;

class Password extends InputFieldRenderer
{
	public function renderInput()
	{
		return FormFacade::password($this->options['name'], $this->options['attributes']);
	}
}