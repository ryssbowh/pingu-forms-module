<?php
namespace Pingu\Forms\Support\Types;

use Illuminate\Database\Eloquent\Builder;
use Pingu\Forms\Support\Type;

class Text extends Type
{
	/**
	 * @inheritDoc
	 */
	public function filterQueryModifier(Builder $query, $value)
	{
		if($value){
			$query->where($this->getFieldName(), 'like', '%'.$value.'%');
		}
	}
}