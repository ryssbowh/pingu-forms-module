<?php
namespace Pingu\Forms\Support\Fields;

use Illuminate\Database\Eloquent\Builder;
use Pingu\Forms\Support\Field;

class Boolean extends Field
{
	/**
	 * @inheritDoc
	 */
	public function setValue($value)
	{
		$this->value = (bool)$value;
	}

	/**
	 * @inheritDoc
	 */
	public static function filterQueryModifier(Builder $query, string $name, $value)
	{
		$query->where($name, '=', (int)$value);
	}
}