<?php 
namespace Pingu\Forms\Support;

use Illuminate\Database\Eloquent\Builder;
use Pingu\Core\Contracts\RenderableWithSuggestions;
use Pingu\Core\Entities\BaseModel;
use Pingu\Forms\Contracts\FormElementContract;

/**
 * Base class for a field type. Will be used as default for fields
 * that don't define a type so we don't have to specify one where
 * most of the types won't need any special behaviour
 */
class Type
{
	protected $field;

	public function construct__(Field $field)
	{
		$this->field = $field;
	}

	public static function addValidationRules()
	{
		return '';
	}

	/**
	 * Sets the value of a field to a model
	 * 
	 * @param BaseModel $model
	 * @param string    $field
	 * @param mixed    $value
	 */
	public static function setModelValue(BaseModel $model, string $field, $value, array $fieldOptions)
	{
		$model->$field = $value;
	}
	
	/**
	 * Modify the query when filtering models on that type
	 * 
	 * @param  Builder $query
	 * @param  string  $name 
	 * @param  mixed  $value
	 */
	public static function filterQueryModifier(Builder $query, string $name, $value)
	{
		if($value){
			$query->where($name, '=', $value);
		}
	}

	/**
	 * Saves a model's relation for that type 
	 * 
	 * @param  BaseModel $model
	 * @param  string    $name
	 * @param  value    $value
	 */
	public static function saveRelationships(BaseModel $model, string $name, $value)
	{
		return false;
	}

	/**
	 * destroys a model's relation for that type
	 * 
	 * @param  BaseModel $model
	 * @param  string    $name
	 */
	public static function destroyRelationships(BaseModel $model, string $name)
	{
		return true;
	}
}