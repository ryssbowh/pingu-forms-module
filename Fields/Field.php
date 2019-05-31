<?php
namespace Pingu\Forms\Fields;

use FormFacade;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Pingu\Core\Entities\BaseModel;

abstract class Field
{
	protected $type;
	protected $name;
	protected $options;
	protected $renderer;

	public function __construct(string $name, array $options = [])
	{
		$this->name = $name;
		$this->type = strtolower(class_basename($this));

		$options['name'] = $name;
		$options['type'] = $this->type;
		
		$this->options = $options;
	}

	/**
	 * Set default value for that field
	 * @param mixed $value
	 */
	public function setDefault($value)
	{
		$this->options['default'] = $value;
	}

	/**
	 * name getter
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Type getter
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * Renders this field
	 * @return string
	 */
	public function render()
	{
		(new $this->options['renderer']($this))->render();
	}

	public function __toString()
	{
		$this->render();
	}

	/**
	 * Set the value for a model for this type of field
	 * @param BaseModel $model
	 * @param string    $field
	 * @param mixed    $value
	 */
	public static function setModelValue(BaseModel $model, string $field, $value)
	{
		$model->$field = $value;
	}

	/**
	 * Options getter
	 * @return array
	 */
	public function getOptions()
	{
		return $this->options;
	}

	/**
	 * Query modifier when filtering model on this type of field
	 * @param  Builder $query
	 * @param  string  $name
	 * @param  mixed  $value
	 * @return void
	 */
	public static function filterQueryModifier(Builder $query, string $name, $value)
	{
		if($value){
			$query->where($name, '=', $value);
		}
	}

	/**
	 * Saves the relationships associated to this type of field
	 * @param  BaseModel $model
	 * @param  string    $name
	 * @param  mixed     $value
	 * @return bool          
	 */
	public static function saveRelationships(BaseModel $model, string $name, $value){
		return false;
	}

	/**
	 * Destroys a relationships for this type of field
	 * @param  BaseModel $model
	 * @param  string    $name
	 * @param  $value $[name] [<description>]
	 * @return bool
	 */
	public static function destroyRelationships(BaseModel $model, string $name){
		return true;
	}

	public function hasOption($name)
	{
		return isset($this->options[$name]);
	}

	public function removeOption(string $name)
	{
		if($this->hasOption($name)) unset($this->option[$name]);
		return $this;
	}

	public function setOption(string $name, $value)
	{
		$this->options[$name] = $value;
		return $this;
	}

	// public function serialize()
	// {
	// 	$data = [
	// 		'class' => classname($this),
	// 		'name' => $this->name,
	// 		'options' => $this->options
	// 	];
	// 	return $data;
	// }
}