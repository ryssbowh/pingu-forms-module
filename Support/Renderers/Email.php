<?php
namespace Pingu\Forms\Support\Renderers;

use Pingu\Forms\Support\Fields\Text;
use Pingu\Forms\Support\FieldRenderer;

class Email extends FieldRenderer
{
	public function __construct(Text $field, array $attributes)
	{
		parent::__construct($field, $attributes);
	}
}