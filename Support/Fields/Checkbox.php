<?php
namespace Pingu\Forms\Support\Fields;

use Pingu\Forms\Support\Field;
use Pingu\Forms\Support\Types\Boolean;

class Checkbox extends Field
{
	/**
	 * @inheritDoc
	 */
	public static function getDefaultType()
	{
		return Boolean::class;
	}
}