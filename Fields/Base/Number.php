<?php
namespace Pingu\Forms\Fields\Base;

use Pingu\Forms\Renderers\Number as NumberRenderer;
use Pingu\Forms\Support\Field;

class Number extends Field
{
	/**
	 * @inheritDoc
	 */
	public function getDefaultRenderer()
	{
		return NumberRenderer::class;
	}
}