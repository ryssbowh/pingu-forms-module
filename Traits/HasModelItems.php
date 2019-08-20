<?php
namespace Pingu\Forms\Traits;

use Illuminate\Database\Eloquent\Collection;
use Pingu\Core\Entities\BaseModel;
use Pingu\Forms\Exceptions\FormFieldException;

trait HasModelItems
{	
	/**
	 * Sets the value for that field
	 * 
	 * @param array|Collection|BaseModel $models
	 */
	public function setValue($models)
	{
		if(is_null($models)){
			$this->value = [];
			return $this;
		}
		if($models instanceof Collection){
			$models = $models->flatten()->all();
		}
		elseif(!is_array($models)){
			$models = [$models];
		}

		$value = [];
		foreach($models as $model){
			if($model instanceof BaseModel){
				$value[] = (string)$model->getKey();
			}
			elseif(is_numeric($model)){
				$value[] = (string)$model;
			}
			else{
				throw FormFieldException::invalidValue($this->name, $model);
			}
		}
		$this->value = $value;
		return $this;
	}
}