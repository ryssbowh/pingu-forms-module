<?php
namespace Modules\Forms\Renderers;

use FormFacade;

class Hidden extends InputFieldRenderer
{
	public function renderInput()
	{
		return FormFacade::hidden($this->options['name'], $this->options['default'] ?? null, $this->options['attributes']);
	}
}