<?php

namespace Pingu\Forms\Traits;

use Pingu\Forms\Contracts\Models\FormableContract;
use Pingu\Forms\Exceptions\FormFieldModelException;

trait HasModelFields
{
	use HasFields;

	public function addModelFields(array $fields, FormableContract $model)
	{
        foreach($fields as $name){
            $this->addModelField($name, $model);
        }
        return $this;
	}

    public function addModelField(string $name, FormableContract $model)
    {
    	$definitions = $model->getFieldDefinitions();
    	if(!isset($definitions[$name])){
    		throw FormFieldModelException::notDefined($name, $model);
    	}
        return $this->addField($name, $definitions[$name]);
    }

}