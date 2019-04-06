<?php
namespace Modules\Forms\Components\Fields;

use FormFacade;
use Illuminate\Database\Eloquent\Builder;
use Modules\Core\Entities\BaseModel;
use Modules\Forms\Exceptions\FieldMissingAttributeException;

class Model extends Select
{
	protected $allowNoValue;
	protected $noValueLabel;

	public function __construct(string $name, array $options = [])
	{
		$options['separator'] = $options['separator'] ?? ' - ';
		parent::__construct($name, $options);
		$this->options['items'] = $this->buildItems();
	}

	protected function buildItems()
	{
		if(!isset($this->options['model'])){
			throw new FieldMissingAttributeException('Field '.$this->name.' is missing a \'model\' option');
		}
		if(!isset($this->options['fields'])){
			throw new FieldMissingAttributeException('Field '.$this->name.' is missing a \'fields\' option');
		}

		$models = $this->options['model']::all();
        $values = [];
        if($this->options['allowNoValue']){
        	$values[] = $this->options['noValueLabel'];
        }
        foreach($models as $model){
            $value = [];
            foreach($this->options['fields'] as $field){
                $value[] = $model->$field;
            }
            $values[$model->id] = implode($this->options['separator'], $value);
        }
        return $values;
	}

	public static function queryFilterApi(Builder $query, string $name, $value)
	{
		if(!$value) return;
		$model = $query->getModel()::fieldDefinitions()[$name]['options']['model'];
		$model = new $model;
		$query->where(str_singular($model->getTable()).'_id', '=', $value);
	}

	public static function setModelValue(BaseModel $model, string $name, $value)
	{
		$modelValue = $model->fieldDefinitions()[$name]['options']['model']::findOrFail($value);
        $model->$name()->associate($modelValue);
	}
}