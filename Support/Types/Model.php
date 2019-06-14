<?php
namespace Pingu\Forms\Support\Types;

use Illuminate\Database\Eloquent\Builder;
use Pingu\Core\Entities\BaseModel;
use Pingu\Forms\Support\Type;

class Model extends Type
{
	/**
	 * @inheritDoc
	 */
	public static function filterQueryModifier(Builder $query, string $name, $value)
	{
		if(!$value) return;
		$model = $query->getModel()->getFieldDefinitions()[$name]['model'];
		$model = new $model;
		$query->where(str_singular($model->getTable()).'_id', '=', $value);
	}

	/**
	 * @inheritDoc
	 */
	public static function setModelValue(BaseModel $model, string $name, $value)
	{
		if(!$value){
			$model->$name()->dissociate();
		}
		else{
			$modelValue = $model->fieldDefinitions()[$name]['options']['model']::findOrFail($value);
        	$model->$name()->associate($modelValue);
        }
	}
}