<?php
namespace Modules\Forms\Components\Fields;

use FormFacade;

class Password extends Field
{

	public function __construct(string $name, array $options = [])
	{
		parent::__construct($name, $options);
	}

	public function renderInput()
	{
		return FormFacade::password($this->name, $this->options['attributes']);
	}
}