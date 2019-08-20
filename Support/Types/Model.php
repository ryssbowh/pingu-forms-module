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
	public function filterQueryModifier(Builder $query, $value)
	{
		if(!$value) return;
		$query->where($this->getFieldName().'_id', '=', $value);
	}

	/**
	 * @inheritDoc
	 */
	public function setModelValue(BaseModel $model, $value)
	{
		$name = $this->getFieldName();
		$modelClass = $this->field->option('model');
		if(!$value){
			$model->$name()->dissociate();
		}
		else{
        	$model->$name()->associate($modelClass::find($value));
        }
	}
}