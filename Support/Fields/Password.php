<?php
namespace Pingu\Forms\Support\Fields;

use Pingu\Forms\Support\Field;
use Pingu\Forms\Support\Types\Password as PasswordType;

class Password extends Field
{
	/**
	 * @inheritDoc
	 */
	public static function getDefaultType()
	{
		return PasswordType::class;
	}
}