<?php
namespace Modules\Forms\Components\Fields;

use FormFacade;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Modules\Core\Entities\BaseModel;
use Modules\Forms\Exceptions\FieldMissingAttributeException;

class ForeignModel extends Model
{

	public static function fieldQueryModifier(Builder $query, string $name, $value)
	{
		if(!$value) return;
		$model = $query->getModel()::fieldDefinitions()[$name]['model'];
		$model = new $model;
		$query->whereHas($name, function($query) use ($model, $value){
            $query->where(str_singular($model->getTable()).'_id', '=', $value);
        });
	}

	public function renderInput()
	{
		return FormFacade::select($this->name.'[]', array_column($this->options['items'],'label','id'), $this->options['default'], $this->options['attributes']);
	}

	public static function setModelValue(BaseModel $model, string $name, $value)
	{
		return true;
	}

	public static function saveRelationships(BaseModel $model, string $name, $value){
		$value = is_array($value) ? $value : [$value];
		$model->$name()->detach();
		foreach($value as $foreignId){
			$model->$name()->attach($foreignId);
		}
		return true;
	}
}