<?php
namespace Pingu\Forms\Support\Types;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Pingu\Core\Entities\BaseModel;
use Pingu\Forms\Exceptions\FormFieldException;
use Pingu\Forms\Support\Form;

class ManyModel extends Model
{
	public $maxValues = false;
	public $minValues = false;

	/**
	 * @inheritDoc
	 */
	public function filterQueryModifier(Builder $query, string $name, $value)
	{
		if(!$value) return;
		$model = $query->getModel()->getFieldDefinitions()[$name]->option('model');
		$model = new $model;
		$query->whereHas($name, function($query) use ($model, $value){
            $query->where(str_singular($model->getTable()).'_id', '=', $value);
        });
	}

	/**
	 * @inheritDoc
	 */
	public function setModelValue(BaseModel $model, string $name, $value)
	{
		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function saveRelationships(BaseModel $model, string $name, $value){
		$model->$name()->sync($value);
		$model->load($name);
		return true;
	}
}