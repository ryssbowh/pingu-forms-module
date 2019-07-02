<?php
namespace Pingu\Forms\Support\Fields;

use Pingu\Forms\Support\Field;

class Password extends Field
{
	/**
	 * @inheritDoc
	 */
	public function getDefaultView()
	{
		return 'forms::fields.'.$this->getType();
	}
}