<?php 
namespace Pingu\Forms\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Pingu\Core\Contracts\RenderableWithSuggestions;
use Pingu\Core\Entities\BaseModel;
use Pingu\Forms\Contracts\FormElementContract;

interface FieldContract extends FormElementContract
{
	public function setValue($value);

	public function getValue();

	/**
	 * Query modifier when filtering model on this type of field
	 * 
	 * @param  Builder $query
	 * @param  string  $name
	 * @param  mixed  $value
	 * @return void
	 */
	public static function filterQueryModifier(Builder $query, string $name, $value);

	/**
	 * Set the value for a model for this type of field
	 * 
	 * @param BaseModel $model
	 * @param string    $field
	 * @param mixed    $value
	 */
	public static function setModelValue(BaseModel $model, string $field, $value);

	/**
	 * Saves the relationships associated to this type of field
	 * 
	 * @param  BaseModel $model
	 * @param  string    $name
	 * @param  mixed     $value
	 * @return bool          
	 */
	public static function saveRelationships(BaseModel $model, string $name, $value);

	/**
	 * Destroys a relationships for this type of field
	 * 
	 * @param  BaseModel $model
	 * @param  string    $name
	 * @return bool
	 */
	public static function destroyRelationships(BaseModel $model, string $name);
}