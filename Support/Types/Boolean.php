<?php
namespace Pingu\Forms\Support\Types;

use Illuminate\Database\Eloquent\Builder;
use Pingu\Forms\Support\Field;
use Pingu\Forms\Support\Type;

class Boolean extends Type
{
	/**
	 * @inheritDoc
	 */
	public function valueForField($value)
	{
		return (bool)$value;
	}

	/**
	 * @inheritDoc
	 */
	public static function filterQueryModifier(Builder $query, string $name, $value)
	{
		if($value){
			$query->where($name, '=', (int)$value);
		}
	}
}