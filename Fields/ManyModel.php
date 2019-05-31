<?php
namespace Pingu\Forms\Fields;

use FormFacade;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Pingu\Core\Entities\BaseModel;
use Pingu\Forms\Exceptions\FieldMissingAttributeException;

class ManyModel extends Model
{
	protected $models;

	public function __construct(string $name, array $options = [])
	{
		$this->models = collect();
		$options['multiple'] = true;
		parent::__construct($name, $options);
	}

	public function getModels()
	{
		return $this->models;
	}

	public function setDefault($models)
	{
		$this->models = $models;
		$default = [];
		if(!is_null($models)){
			foreach($models as $item){
				$default[] = (string)$item->id;
			}
		}
		$this->options['default'] = $default;
	}

	public static function filterQueryModifier(Builder $query, string $name, $value)
	{
		if(!$value) return;
		$model = $query->getModel()::fieldDefinitions()[$name]['model'];
		$model = new $model;
		$query->whereHas($name, function($query) use ($model, $value){
            $query->where(str_singular($model->getTable()).'_id', '=', $value);
        });
	}

	public static function setModelValue(BaseModel $model, string $name, $value)
	{
		return true;
	}

	public static function saveRelationships(BaseModel $model, string $name, $value){
		$model->$name()->sync($value);
		$model->load($name);
		return true;
	}
}