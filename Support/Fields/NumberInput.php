<?php
namespace Pingu\Forms\Support\Fields;

use Pingu\Forms\Support\Field;
use Pingu\Forms\Support\Types\Integer;

class NumberInput extends Field
{
	/**
	 * @inheritDoc
	 */
	public function getDefaultType()
	{
		return Integer::class;
	}
}