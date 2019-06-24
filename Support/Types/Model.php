<?php
namespace Pingu\Forms\Support\Types;

use Illuminate\Database\Eloquent\Builder;
use Pingu\Core\Entities\BaseModel;
use Pingu\Forms\Support\Type;

class Model extends Type
{
	/**
	 * @inheritDoc
	 */
	public function filterQueryModifier(Builder $query, string $name, $value)
	{
		if(!$value) return;
		$table = $this->field->option('model')::tableName();
		$query->where(str_singular($table).'_id', '=', $value);
	}

	/**
	 * @inheritDoc
	 */
	public function setModelValue(BaseModel $model, string $name, $value)
	{
		if(!$value){
			$model->$name()->dissociate();
		}
		else{
			$modelValue = $this->field->option('model')::findOrFail($value);
        	$model->$name()->associate($modelValue);
        }
	}
}