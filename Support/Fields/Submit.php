<?php
namespace Pingu\Forms\Support\Fields;

use Pingu\Forms\Support\NoValueField;

class Submit extends NoValueField
{
	public function __construct(string $name, array $options = [], array $attributes = [])
	{
		$options['label'] = $options['label'] ?? 'Submit';
		parent::__construct($name, $options, $attributes);
	}
}