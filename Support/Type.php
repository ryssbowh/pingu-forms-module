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

	public function __construct(Field $field)
	{
		$this->field = $field;
	}

	public function getFieldName()
	{
		return $this->field->getName();
	}

	/**
	 * Sets the value of a field to a model
	 * 
	 * @param BaseModel $model
	 * @param mixed    $value
	 */
	public function setModelValue(BaseModel $model, $value)
	{
		$name = $this->getFieldName();
		$model->$name = $value;
	}
	
	/**
	 * Modify the query when filtering models on that type
	 * 
	 * @param  Builder $query
	 * @param  string  $name 
	 * @param  mixed  $value
	 */
	public function filterQueryModifier(Builder $query, $value)
	{
		if($value){
			$query->where($this->getFieldName(), '=', $value);
		}
	}

	/**
	 * Saves a model's relation for that type.
	 * 
	 * @param  BaseModel $model
	 * @param  string    $name
	 * @param  value    $value Have changes been made.
	 */
	public function saveRelationships(BaseModel $model, $value)
	{
		return;
	}

	/**
	 * destroys a model's relation for that type
	 * 
	 * @param  BaseModel $model
	 * @param  string    $name
	 */
	// public function destroyRelationships(BaseModel $model, string $name)
	// {
	// 	return;
	// }
}