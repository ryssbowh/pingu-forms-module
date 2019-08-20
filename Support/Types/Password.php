<?php
namespace Pingu\Forms\Support\Types;

use Illuminate\Database\Eloquent\Builder;
use Pingu\Forms\Support\Type;

class Password extends Type
{
	/**
	 * @inheritDoc
	 */
	public function filterQueryModifier(Builder $query, $value)
	{
		if($value){
			$query->where($this->getFieldName(), '=', '%'.$value.'%');
		}
	}

	public function setFieldValue(BaseModel $model, string $value)
	{
		$name = $this->getFieldName();
		$model->$name = \Hash::make($value);
	}
}