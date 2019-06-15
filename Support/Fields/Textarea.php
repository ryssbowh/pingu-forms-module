<?php
namespace Pingu\Forms\Support\Fields;

class Textarea extends TextInput
{
	/**
	 * @inheritDoc
	 */
	public function getDefaultView()
	{
		return 'forms::fields.'.$this->getType();
	}
}