<?php
namespace Pingu\Forms\Support\Types;

use Illuminate\Database\Eloquent\Builder;
use Pingu\Forms\Support\Type;

class Datetime extends Type
{
	/**
	 * @inheritDoc
	 */
	public function filterQueryModifier(Builder $query, $value)
	{
		if(!$value) return;
		$name = $this->getFieldName();
		if(isset($value['from']) and $value['from']){
			$query->where($name, '>=', $value['from']);
		}
		if(isset($value['to']) and $value['to']){
			$query->where($name, '<=', $value['to']);
		}
	}
}