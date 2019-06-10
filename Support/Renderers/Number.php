<?php
namespace Pingu\Forms\Renderers;

use Pingu\Forms\Support\Fields\Integer;
use Pingu\Forms\Support\FieldRenderer;

class Number extends FieldRenderer
{
	public function __construct(Integer $field, array $attributes)
	{
		parent::__construct($field, $attributes);
		$this->attributes->add('step', $field->precision ?? 'any');
	}
}