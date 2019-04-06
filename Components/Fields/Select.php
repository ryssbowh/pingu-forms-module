<?php
namespace Modules\Forms\Components\Fields;

use FormFacade;

class Select extends Field
{
	protected $values;

	public function __construct(string $name, array $options = [])
	{
		$options['allowNoValue'] = $options['allowNoValue'] ?? true;
		$options['noValueLabel'] = $options['noValueLabel'] ?? 'Select';
		$options['items'] = $options['items'] ?? [];
		parent::__construct($name, $options);
	}

	public function renderInput()
	{
		return FormFacade::select($this->name, $this->values, $this->default, $this->attributes);
	}
}