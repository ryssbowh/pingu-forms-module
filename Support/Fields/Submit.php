<?php
namespace Pingu\Forms\Support\Fields;

use Pingu\Forms\Support\Field;

class Submit extends Field
{
	public function __construct(string $name, array $options = [], array $attributes = [])
	{
		$options['label'] = $options['label'] ?? 'Submit';
		parent::__construct($name, $options, $attributes);
	}

	/**
	 * @inheritDoc
	 */
	public function getDefaultView()
	{
		return 'forms::fields.'.$this->getType();
	}
}