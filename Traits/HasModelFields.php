<?php
namespace Pingu\Forms\Traits;

use Illuminate\Database\Eloquent\Builder;
use Pingu\Core\Entities\BaseModel;
use Pingu\Forms\Contracts\Models\FormableContract;
use Pingu\Forms\Exceptions\FormFieldException;
use Pingu\Forms\Traits\HasFields;

trait HasModelFields
{
    use HasFields;

    /**
     * build fields for a model
     * 
     * @param  array  $fields
     * @return Form
     */
    protected function makeFields(array $fields)
    {
        $this->fields = collect();
        $definitions = $this->model->getFieldDefinitions();
        foreach($fields as $name){
            if(!isset($definitions[$name])){
                throw FormFieldException::notDefinedInModel($name, get_class($this->model));
            }
            $field = $this->_addField($name, $definitions[$name]);
            
            if($this->model->exists){
                $field->setValue($this->model->$name);
            }
        }
        return $this;
    }

    /**
     * Ads fields from a model to this form
     * 
     * @param array            $fields
     * @param FormableContract $model
     * @param string           $group
     * @return Form
     */
	public function addModelFields(array $fields, FormableContract $model, $group = 'default')
	{
        foreach($fields as $name){
            $this->addModelField($name, $model, $group);
        }
        return $this;
	}

    /**
     * Adds a field from a model to this form
     * 
     * @param string           $name
     * @param FormableContract $model
     * @param string           $group
     * @return Form
     */
    public function addModelField(string $name, FormableContract $model, $group = 'default')
    {
    	$definitions = $model->getFieldDefinitions();
    	if(!isset($definitions[$name])){
    		throw FormFieldException::notDefinedInModel($name, get_class($model));
    	}
        $field = $this->addField($name, $definitions[$name], $group);
        if($model->exists){
            $field->setValue($model->$name);
        }
        return $field;
    }
}