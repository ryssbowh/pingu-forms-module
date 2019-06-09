<?php
namespace Pingu\Forms\Traits;

use Illuminate\Database\Eloquent\Builder;
use Pingu\Core\Entities\BaseModel;

trait ModelField
{
	public static function setModelValue(BaseModel $model, string $field, $value)
	{
		$model->$field = $value;
	}

	
	public static function filterQueryModifier(Builder $query, string $name, $value)
	{
		if($value){
			$query->where($name, '=', $value);
		}
	}

	public static function saveRelationships(BaseModel $model, string $name, $value)
	{
		return false;
	}

	public static function destroyRelationships(BaseModel $model, string $name)
	{
		return true;
	}

}