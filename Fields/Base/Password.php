<?php
namespace Pingu\Forms\Fields\Base;

use Hash;
use Pingu\Forms\Renderers\Password as PasswordRenderer;
use Pingu\Forms\Support\Field;

class Password extends Field
{

	/**
	 * @inheritDoc
	 */
	public function getDefaultRenderer()
	{
		return PasswordRenderer::class;
	}

}