<?php
namespace Pingu\Forms\Support\Fields;

use Pingu\Forms\Support\Field;
use Pingu\Forms\Support\Types\Datetime as DatetimeType;

class Datetime extends Field
{	
	/**
	 * @inheritDoc
	 */
	public static function getDefaultType()
	{
		return DatetimeType::class;
	}
}