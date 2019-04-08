<?php
namespace Modules\Forms\Components\Fields;

use FormFacade;

class Select extends Field
{
	public function __construct(string $name, array $options = [])
	{
		$options['allowNoValue'] = $options['allowNoValue'] ?? true;
		$options['noValueLabel'] = $options['noValueLabel'] ?? 'Select';
		$options['multiple'] = $options['multiple'] ?? false;
		$options['items'] = $options['items'] ?? [];
		parent::__construct($name, $options);
		$this->options['attributes']['multiple'] = $options['multiple'];
	}

	public function renderInput()
	{
		return FormFacade::select($this->name.'[]', $this->options['items'], $this->options['default'], $this->options['attributes']);
	}
}