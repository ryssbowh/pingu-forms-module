<?php

namespace Pingu\Forms;

use FormFacade;
use Pingu\Core\Entities\BaseModel;
use Pingu\Forms\Events\FormBuilt;
use Pingu\Forms\Exceptions\FieldMissingAttributeException;
use Pingu\Forms\Exceptions\FormNotBuiltException;

class FormModel extends Form
{
    protected $model;
    protected $edit = true;
    protected $fieldDefinitions;

    /**
     * Creates a new form. Set the default options (views, layout) and build the fields (if provided).
     * For a model form, if the fields aren't given, will default to the model's fillable fields
     * 
     * @param array       $attributes
     * @param array       $options
     * @param BaseModel|null $model
     * @param array|null  $fields
     */
    public function __construct(array $attributes, ?array $options = [], $model, ?array $fields = null, $name = null)
    {   
        $this->model = $model;
        if(!is_object($model)){
            $this->edit = false;
            $this->model = new $model();
        }
        $this->fieldDefinitions = $this->model::fieldDefinitions();
        if(is_null($fields)){
            if($this->edit) $fields = $this->model->editFormFields();
            else $fields = $this->model->addFormFields();
        }

        if(is_null($name)){
            $name = $this->generateName();
        }

        parent::__construct($name, $attributes, $options, $fields);
    }

    /**
     * Generates a name for this form
     * @return string
     */
    protected function generateName()
    {
        return ($this->edit ? 'edit-' : 'add-') . kebab_case($this->model::friendlyName());
        
    }

    /**
     * Get the model associated with this form
     * @return BaseModel
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Adds the model's field (found in $model->fieldDefinitions()) to this form
     * 
     * @param $fields
     */
    public function addModelField(string $field)
    {
        $options = $this->fieldDefinitions[$field];
        if($this->edit) $options['default'] = $this->model->$field;
        $this->addField($field, $options);
    }

    public function addFields(array $fields)
    {
        foreach($fields as $name){
            $this->addModelField($name);
        }
    }

    /**
     * print form's opening
     *
     * @see  https://laravelcollective.com/docs/5.4/html
     * @throws FormNotBuiltException
     * @return void
     */
    public function printStart()
    {
        echo FormFacade::model($this->model, $this->attributes);
        echo FormFacade::hidden('_name', $this->name);
    }

}