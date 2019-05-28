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
		$models = $this->options['model']::all();
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
	public static function fieldQueryModifier(Builder $query, string $name, $value)
	{
		if(!$value) return;
		$model = $query->getModel()::fieldDefinitions()[$name]['model'];
		$model = new $model;
		$query->where(str_singular($model->getTable()).'_id', '=', $value);
	}

	/**
	 * @inheritDoc
	 */
	public static function setModelValue(BaseModel $model, string $name, $value)
	{
		if(is_null($value)){
			$model->$name()->dissociate();
		}
		else{
			$modelValue = $model->fieldDefinitions()[$name]['model']::findOrFail($value);
        	$model->$name()->associate($modelValue);
        }
	}
}