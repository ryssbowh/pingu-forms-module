<?php
namespace Pingu\Forms\Renderers;

use Pingu\Forms\Contracts\FieldContract;
use Pingu\Forms\Renderers\FieldRenderer;

class Number extends FieldRenderer
{
	public function __construct(FieldContract $field)
	{
		parent::__construct($field);
		$this->field->attributes->add('step', $field->precision ?? 'any');
	}
}