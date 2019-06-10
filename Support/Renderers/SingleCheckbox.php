<?php
namespace Pingu\Forms\Renderers;

use Pingu\Forms\Support\Fields\Boolean;
use Pingu\Forms\Support\FieldRenderer;

class SingleCheckbox extends FieldRenderer
{
	public function __construct(Boolean $field, array $attributes)
	{
		parent::__construct($field, $attributes);
	}
}