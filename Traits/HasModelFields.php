<?php
namespace Pingu\Forms\Traits;

use Illuminate\Database\Eloquent\Builder;
use Pingu\Core\Entities\BaseModel;
use Pingu\Forms\Contracts\Models\FormableContract;
use Pingu\Forms\Exceptions\FormFieldException;
use Pingu\Forms\Support\Field;
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
        $definitions = $this->model->buildFieldDefinitions();
        foreach($fields as $name){
            if(!isset($definitions[$name])){
                throw FormFieldException::notDefinedInModel($name, get_class($this->model));
            }
            $field = $this->_addField($name, $definitions[$name]);
            
            if($this->editing){
                $field->setValue($this->model->getFormValue($name));
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
    	$definitions = $model->buildFieldDefinitions();
    	if(!isset($definitions[$name])){
    		throw FormFieldException::notDefinedInModel($name, get_class($model));
    	}
        $field = $definitions[$name];
        if($model->exists){
            $field->setValue($model->getFormValue($name));
        }
        $this->addField($field, $group);
        return $this;
    }
}