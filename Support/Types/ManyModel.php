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
	public function filterQueryModifier(Builder $query, $value)
	{
		if(!$value) return;
		$name = $this->getFieldName();
		$model = $query->getModel()->buildFieldDefinitions()[$name]->option('model');
		$model = new $model;
		$query->whereHas($name, function($query) use ($model, $value){
            $query->where(str_singular($model->getTable()).'_id', '=', $value);
        });
	}

	/**
	 * @inheritDoc
	 */
	public function setModelValue(BaseModel $model, $value)
	{
		return;
	}

	/**
	 * Takes an input and turn it into an array of integers if the values are numeric.
	 * Need to do that as values coming from a form are always strings.
	 * 
	 * @param  mixed $input
	 * @return array
	 */
	protected function sanitizeValue($input)
	{
		return array_map(function($item){
			return is_numeric($item) ? (int)$item : $item;
		}, $input);
	}

	/**
	 * Saves relationships for a model.
	 * We first sanitize the coming value and check if the current 
	 * relationships holds the same values as the new value. 
	 * If so we return false to indicate that no changes have been made.
	 * 
	 * @param  BaseModel $model
	 * @param  string    $name
	 * @param  mixed     $value
	 * @return bool
	 */
	public function saveRelationships(BaseModel $model, $value){
		$name = $this->getFieldName();
		$foreignKey = $model->$name()->getRelatedKeyName();
		$currentValue = $model->$name->pluck($foreignKey)->toArray();
		$value = $this->sanitizeValue($value);
		if($value == $currentValue){
			return false;
		}
		$model->$name()->sync($value);
		$model->load($name);
		return true;
	}
}