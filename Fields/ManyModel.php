<?php
namespace Modules\Forms\Fields;

use FormFacade;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Modules\Core\Entities\BaseModel;
use Modules\Forms\Exceptions\FieldMissingAttributeException;

class ManyModel extends Model
{
	public function __construct(string $name, array $options = [])
	{
		$options['multiple'] = true;
		parent::__construct($name, $options);
	}

	public function setDefault($models)
	{
		$default = [];
		if(!is_null($models)){
			foreach($models as $item){
				$default[] = (string)$item->id;
			}
		}
		$this->options['default'] = $default;
	}

	public static function fieldQueryModifier(Builder $query, string $name, $value)
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
		$value = is_array($value) ? $value : [$value];
		$return = false;

		$previouslyAttached = $model->$name->map(function($foreign){
			return $foreign->id;
		})->toArray();

		foreach($value as $foreignId){
			if(!$model->$name->contains($foreignId)){
				$return = true;
				$model->$name()->attach($foreignId);
			}
		}

		$toDetach = array_diff($previouslyAttached, $value);
		foreach($toDetach as $id){
			$model->$name()->detach($id);
			$return = true;
		}

		return $return;
	}
}