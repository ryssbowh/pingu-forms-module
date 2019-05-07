<?php
namespace Pingu\Forms\Renderers;

use FormFacade;

class Email extends InputFieldRenderer
{
	public function renderInput()
	{
		return FormFacade::email($this->options['name'], $this->options['default'] ?? null, $this->options['attributes']);
	}
}