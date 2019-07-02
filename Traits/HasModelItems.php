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
		if($models instanceof BaseModel){
			$models = [$models];
		}
		if(!is_array($models)){
			throw FormFieldException::notAnArray($this->name, $models);
		}
		foreach($models as $model){
			if(!$model instanceof BaseModel){
				throw FormFieldException::notABaseModel($this->name, $model); 
			}
		}
		$value = [];
		foreach($models as$model){
			$value[] = (string)$model->getKey();
		}
		$this->value = $value;
		return $this;
	}
}