<?php
namespace Pingu\Forms\Renderers;

use FormFacade;

class Text extends InputFieldRenderer
{
	public function renderInput()
	{
		return FormFacade::text($this->options['name'], $this->options['default'] ?? null, $this->options['attributes']);
	}
}