<?php
namespace Modules\Forms\Fields;

use FormFacade;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Modules\Core\Entities\BaseModel;

abstract class Field
{
	protected $type;
	protected $name;
	protected $options;
	protected $renderer;

	public function __construct(string $name, array $options = [])
	{
		$this->name = $name;
		$this->type = strtolower(classname($this));

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
		(new $this->options['renderer']($this->options))->render();
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
	public static function fieldQueryModifier(Builder $query, string $name, $value)
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
		return true;
	}
}