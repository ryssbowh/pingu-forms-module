<?php
namespace Modules\Forms\Components\Fields;

use FormFacade;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Modules\Core\Entities\BaseModel;
use Modules\Forms\Exceptions\FieldMissingAttributeException;

class Model extends Select
{

	public function __construct(string $name, array $options = [])
	{
		$options['separator'] = $options['separator'] ?? ' - ';

		if(!isset($options['model'])){
			throw new FieldMissingAttributeException('Field '.$name.' is missing a \'model\' option');
		}
		if(!isset($options['textField'])){
			throw new FieldMissingAttributeException('Field '.$name.' is missing a \'textField\' option');
		}

		parent::__construct($name, $options);
		$this->options['items'] = $this->buildItems();
	}

	protected function buildItems()
	{
		$models = $this->options['model']::all();
        $values = [];
        if($this->options['allowNoValue']){
        	$values[] = [
        		'id' => '',
        		'label' => $this->options['noValueLabel']
        	];
        }
        foreach($models as $model){
            $values[] = [
            	'id' => $model->id, 
            	'label' => implode($this->options['separator'], $model->only($this->options['textField']))
            ];
        }
        return $values;
	}

	public function renderInput()
	{
		return FormFacade::select($this->name, array_column($this->options['items'],'label','id'), $this->options['default'], $this->options['attributes']);
	}

	public static function fieldQueryModifier(Builder $query, string $name, $value)
	{
		if(!$value) return;
		$model = $query->getModel()::fieldDefinitions()[$name]['model'];
		$model = new $model;
		$query->where(str_singular($model->getTable()).'_id', '=', $value);
	}

	public static function setModelValue(BaseModel $model, string $name, $value)
	{
		$modelValue = $model->fieldDefinitions()[$name]['model']::findOrFail($value);
        $model->$name()->associate($modelValue);
	}
}