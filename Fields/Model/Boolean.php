<?php
namespace Pingu\Forms\Fields\Model;

use Illuminate\Database\Eloquent\Builder;
use Pingu\Forms\Contracts\ModelFieldContract;
use Pingu\Forms\Fields\Base\Boolean as Base;
use Pingu\Forms\Traits\ModelField;

class Boolean extends Base implements ModelFieldContract
{
	use ModelField;
	/**
	 * @inheritDoc
	 */
	public static function filterQueryModifier(Builder $query, string $name, $value)
	{
		$query->where($name, '=', (int)$value);
	}
}