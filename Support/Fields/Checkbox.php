<?php
namespace Pingu\Forms\Support\Fields;

use Pingu\Forms\Support\Field;
use Pingu\Forms\Support\Types\Boolean;

class Checkbox extends Field
{
	/**
	 * @inheritDoc
	 */
	public function getDefaultType()
	{
		return Boolean::class;
	}
}