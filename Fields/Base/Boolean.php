<?php
namespace Pingu\Forms\Fields\Base;

use Pingu\Forms\Renderers\SingleCheckbox;
use Pingu\Forms\Support\Field;

class Boolean extends Field
{
	/**
	 * @inheritDoc
	 */
	public function getDefaultRenderer()
	{
		return SingleCheckbox::class;
	}
}