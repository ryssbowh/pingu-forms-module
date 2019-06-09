<?php
namespace Pingu\Forms\Fields\Base;

use Illuminate\Database\Eloquent\Builder;
use Pingu\Forms\Renderers\Text as TextRenderer;
use Pingu\Forms\Support\Field;

class Text extends Field
{
	/**
	 * @inheritDoc
	 */
	public function getDefaultRenderer()
	{
		return TextRenderer::class;
	}
}