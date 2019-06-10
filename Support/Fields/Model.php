<?php
namespace Pingu\Forms\Support\Fields;

use Illuminate\Database\Eloquent\Builder;
use Pingu\Core\Entities\BaseModel;
use Pingu\Forms\Exceptions\FieldMissingAttributeException;
use Pingu\Forms\Exceptions\FormFieldException;
use Pingu\Forms\Support\Form;

class Model extends Serie
{
	public $separator = ' - ';
	public $model;
	public $queryCallback;
	public $textField;
	public $multiple = false;

	public function __construct(string $name, array $options = [], ?Form $form = null)
	{
		if(!isset($options['model'])){
			throw new FieldMissingAttributeException('Field '.$name.' is missing a \'model\' option');
		}
		if(!isset($options['textField'])){
			throw new FieldMissingAttributeException('Field '.$name.' is missing a \'textField\' option');
		}

		if(!is_array($options['textField'])) $options['textField'] = [$options['textField']];
		
		parent::__construct($name, $options, $form);
	}

	/**
	 * @inheritDoc
	 */
	public function setValue($model)
	{
		if(!$this->required and is_null($model)){
			return $this;
		}
		if($model instanceof BaseModel){
			$this->value = $model->getKey();
		}
		elseif(is_int($model)){
			$this->value = $this->model::findOrFail($model)->getKey();
		}
		else{
			throw FormFieldException::notABaseModel($this->name, $model);
		}
		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function buildItems()
	{
		$callback = isset($this->queryCallback) ? $this->queryCallback : false;

		if($callback and method_exists($callback[0], $callback[1])){
			$models = call_user_func($callback, $this);
		}
		else{
			$models = $this->model::all();
		}
        $values = [];
        if($this->allowNoValue){
        	$values[''] = $this->noValueLabel;
        }
        foreach($models as $model){
            $values[''.$model->id] = implode($this->separator, $model->only($this->textField));
        }
        return $values;
	}

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