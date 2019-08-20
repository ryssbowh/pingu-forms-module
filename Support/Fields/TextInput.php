<?php
namespace Pingu\Forms\Support\Fields;

use Pingu\Forms\Support\Field;
use Pingu\Forms\Support\Types\Text;

class TextInput extends Field
{
	/**
	 * @inheritDoc
	 */
	public static function getDefaultType()
	{
		return Text::class;
	}
}