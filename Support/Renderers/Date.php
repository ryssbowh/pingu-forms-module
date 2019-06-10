<?php
namespace Pingu\Forms\Support\Renderers;

use Pingu\Forms\Support\FieldRenderer;
use Pingu\Forms\Support\Fields\Datetime;

class Date extends FieldRenderer
{
	public function __construct(Datetime $field, array $attributes)
	{
		parent::__construct($field, $attributes);
	}
}