<?php
namespace Pingu\Forms\Renderers;

use Pingu\Forms\Fields\Field;

class Number extends FieldRenderer
{
	public function __construct(Field $field)
	{
		parent::__construct($field);
		$this->options['rendererAttributes']['step'] = $this->option['precision'] ?? 'any';
	}
}