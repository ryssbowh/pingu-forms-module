<?php
namespace Modules\Forms\Components\Fields;

use FormFacade;

class Email extends Text
{
	public function renderInput()
	{
		return FormFacade::email($this->name, $this->options['default'], $this->options['attributes']);
	}
}