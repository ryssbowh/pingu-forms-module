<?php
namespace Pingu\Forms\Renderers;

use Pingu\Forms\Support\Fields\Text;
use Pingu\Forms\Support\FieldRenderer;

class Text extends FieldRenderer
{
	public function __construct(Text $field, array $attributes)
	{
		parent::__construct($field, $attributes);
	}
}