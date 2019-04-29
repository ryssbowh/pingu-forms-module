<?php
/**
 * Form provides with helpers to build a form and print it with the help of the laravel collective Form Facade.
 * Example of use :
 * 
 * $form = new Form('test', ['route' => 'my.route'], ['submit' => 'Go!'], MyModel::class, 2);
 * $form->end();
 *
 * In your template :
 * $form->printAll();
 *
 * @package Forms
 * @author  Boris Blondin
 * @version 1.0
 * @see  https://laravelcollective.com/docs/5.4/html
 */

namespace Modules\Forms;

use FormFacade;
use Modules\Core\Entities\BaseModel;
use Modules\Forms\Events\FormBuilt;
use Modules\Forms\Exceptions\FieldMissingAttributeException;
use Modules\Forms\Exceptions\FormNotBuiltException;

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
    public function __construct(array $attributes, ?array $options = [], $model = null, ?array $fields = null, $name = null)
    {   
        $this->attributes = $attributes;
        $this->options = array_merge( $this->defaults, $options);

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
        $this->addFields($fields);
        $this->options['layout'] = $fields;

        if(is_null($name)){
            $name = $this->generateName();
        }

        $this->name = $name;
        $this->attributes['class'] = isset($this->attributes['class']) ? $this->attributes['class'].= ' form form-'.$this->name : 'form form-'.$this->name;


        if(!isset($this->options['layout'])) $this->options['layout'] = $fields;
    }

    protected function generateName()
    {
        return ($this->edit ? 'edit-' : 'add-') . kebab_case($this->model::friendlyName());
        
    }

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