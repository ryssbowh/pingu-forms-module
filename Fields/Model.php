<?php
namespace Pingu\Forms\Fields;

use FormFacade;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Pingu\Core\Entities\BaseModel;
use Pingu\Forms\Exceptions\FieldMissingAttributeException;

class Model extends Serie
{

	public function __construct(string $name, array $options = [])
	{
		$options['separator'] = $options['separator'] ?? ' - ';
		if(isset($options['allowNoValue'])){
			$options['noValueLabel'] = $options['noValueLabel'] ?? config('forms.noValueLabel');
		}

		if(!isset($options['model'])){
			throw new FieldMissingAttributeException('Field '.$name.' is missing a \'model\' option');
		}
		if(!isset($options['textField'])){
			throw new FieldMissingAttributeException('Field '.$name.' is missing a \'textField\' option');
		}

		parent::__construct($name, $options);
	}

	/**
	 * @inheritDoc
	 */
	public function buildItems()
	{
		$callback = isset($this->options['queryCallback']) ? $this->options['queryCallback'] : false;

		if($callback and method_exists($callback[0], $callback[1])){
			$models = call_user_func($callback, $this);
		}
		else{
			$models = $this->options['model']::all();
		}
        $values = [];
        if($this->options['allowNoValue']){
        	$values[''] = $this->options['noValueLabel'];
        }
        foreach($models as $model){
            $values[''.$model->id] = implode($this->options['separator'], $model->only($this->options['textField']));
        }
        return $values;
	}

	/**
	 * @inheritDoc
	 */
	public function setDefault($model)
	{
		$this->options['default'] = $model ? $model->id : null;
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