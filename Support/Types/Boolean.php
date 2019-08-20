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
	public function filterQueryModifier(Builder $query, $value)
	{
		if($value){
			$value = $value == 'true' ? 1 : 0;
			$query->where($this->getFieldName(), '=', $value);
		}
	}
}