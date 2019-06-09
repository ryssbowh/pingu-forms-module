<?php
namespace Pingu\Forms\Fields\Model;

use FormFacade;
use Illuminate\Database\Eloquent\Builder;
use Pingu\Core\Entities\BaseModel;
use Pingu\Forms\Contracts\ModelFieldContract;
use Pingu\Forms\Fields\Base\Serie as Base;
use Pingu\Forms\Traits\ModelField;

class Model extends Base implements ModelFieldContract
{
	use ModelField;

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
			$modelValue = $model->getFieldDefinitions()[$name]['model']::findOrFail($value);
        	$model->$name()->associate($modelValue);
        }
	}
}