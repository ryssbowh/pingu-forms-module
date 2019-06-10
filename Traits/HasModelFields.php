<?php
namespace Pingu\Forms\Traits;

use Illuminate\Database\Eloquent\Builder;
use Pingu\Core\Entities\BaseModel;

trait HasModelFields
{
	protected function _addModelFields(array $fields, FormableContract $model)
    {
        foreach($fields as $name){
            $this->_addModelField($name, $model);
        }
        return $this;
    }

    protected function _addModelField(string $name, FormableContract $model)
    {
        $definitions = $model->getFieldDefinitions();
        if(!isset($definitions[$name])){
            throw FormFieldModelException::notDefined($name, $model);
        }
        $field = $this->_addField($name, $definitions[$name]);
        $field->setValue($model->$name);
        return $field;
    }

	public function addModelFields(array $fields, FormableContract $model, $group = 'default')
	{
        foreach($fields as $name){
            $this->addModelField($name, $model, $group);
        }
        return $this;
	}

    public function addModelField(string $name, FormableContract $model, $group = 'default')
    {
    	$definitions = $model->getFieldDefinitions();
    	if(!isset($definitions[$name])){
    		throw FormFieldModelException::notDefined($name, $model);
    	}
        $field = $this->addField($name, $definitions[$name], $group);
        $field->setValue($model->$name);
        return $field;
    }

}