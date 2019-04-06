<?php
namespace Modules\Forms\Components\Fields;

use FormFacade;

class Number extends Field
{

	public function __construct(string $name, array $options = [])
	{
		parent::__construct($name, $options);
	}

	public function renderInput()
	{
		return FormFacade::number($this->name, $this->options['default'], $this->options['attributes']);
	}
}