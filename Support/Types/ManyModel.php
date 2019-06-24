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
	// public function setValue($models)
	// {
	// 	if($models instanceof Collection){
	// 		$models = $models->flatten()->all();
	// 	}
	// 	if(!is_array($models)){
	// 		throw FormFieldException::notAnArray($this->name, $models);
	// 	}
	// 	foreach($models as $model){
	// 		if(!$model instanceof BaseModel){
	// 			throw FormFieldException::notABaseModel($this->name, $model); 
	// 		}
	// 	}
	// 	$value = [];
	// 	foreach($models as$model){
	// 		$value[] = $model->getKey();
	// 	}
	// 	$this->value = $value;
	// 	return $this;
	// }

	/**
	 * @inheritDoc
	 */
	public function filterQueryModifier(Builder $query, string $name, $value)
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