<?php
namespace Pingu\Forms\Support\Fields;

use Pingu\Forms\Support\Field;

class Link extends Field
{
	protected $required = ['label', 'url'];

	/**
	 * @inheritDoc
	 */
	public function getDefaultView()
	{
		return 'forms::fields.'.$this->getType();
	}
}