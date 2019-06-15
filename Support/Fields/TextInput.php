<?php
namespace Pingu\Forms\Support\Fields;

use Pingu\Forms\Support\Field;

class TextInput extends Field
{
	/**
	 * @inheritDoc
	 */
	public function getDefaultView()
	{
		return 'forms::fields.'.$this->getType();
	}
}