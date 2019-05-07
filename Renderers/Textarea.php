<?php
namespace Pingu\Forms\Renderers;

use FormFacade;

class Textarea extends InputFieldRenderer
{
	public function renderInput()
	{
		return FormFacade::textarea($this->options['name'], $this->options['default'] ?? null, $this->options['attributes']);
	}
}