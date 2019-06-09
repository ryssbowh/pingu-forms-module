<?php
namespace Pingu\Forms\Fields\Model;

use Illuminate\Database\Eloquent\Builder;
use Pingu\Core\Entities\BaseModel;
use Pingu\Forms\Contracts\ModelFieldContract;
use Pingu\Forms\Fields\Base\ManyModel as Base;
use Pingu\Forms\Traits\ModelField;

class ManyModel extends Base implements ModelFieldContract
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
		$query->whereHas($name, function($query) use ($model, $value){
            $query->where(str_singular($model->getTable()).'_id', '=', $value);
        });
	}

	/**
	 * @inheritDoc
	 */
	public static function setModelValue(BaseModel $model, string $name, $value)
	{
		return true;
	}

	/**
	 * @inheritDoc
	 */
	public static function saveRelationships(BaseModel $model, string $name, $value){
		$model->$name()->sync($value);
		$model->load($name);
		return true;
	}
}