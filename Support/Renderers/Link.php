<?php
namespace Pingu\Forms\Renderers;

use Pingu\Forms\Support\Macro;
use Pingu\Forms\Support\FieldRenderer;

class Link extends FieldRenderer
{
	public $secure = false;

	public function __construct(Macro $field, array $attributes)
	{
		parent::__construct($field, $attributes);
	}
}