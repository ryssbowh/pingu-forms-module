<?php
namespace Pingu\Forms\Fields\Base;

use Pingu\Forms\Renderers\Email as EmailRenderer;
use Pingu\Forms\Support\Field;

class Email extends Field
{
	/**
	 * @inheritDoc
	 */
	public function getDefaultRenderer()
	{
		return EmailRenderer::class;
	}
}