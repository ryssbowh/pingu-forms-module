<?php

namespace Pingu\Forms\Support;

use Pingu\Core\Entities\BaseModel;
use Pingu\Forms\Contracts\Models\FormableContract;
use Pingu\Forms\Traits\HasModelFields;

class ModelForm extends Form
{
    use HasModelFields;

    public $model;
    protected $url;
    protected $method;
    protected $name;
    protected $fieldList;
    protected $groupList;
    protected $attributeList;
    protected $editing;

    public function __construct(array $url, string $method, FormableContract $model, $fields = [], ?string $name = null, array $attributes = [], array $groups = [])
    {
        $this->editing = $model->exists;
        $this->model = $model;
        $this->fieldList = $fields;
    	if(!$fields){
    		$this->fieldList = $this->editing ? $model->getEditFormFields() : $model->getAddFormFields();
    	}
    	$this->setName($name);
    	$this->url = $url;
    	$this->method = $method;
    	$this->groupList = $groups;
    	$this->attributeList = $attributes;
    	parent::__construct();
    }

    /**
     * Is this form editing or adding a model
     * 
     * @return boolean
     */
    public function isEditing()
    {
        return $this->editing;
    }

    /**
     * Sets the name of this form
     * 
     * @param string $name
     * @return ModelForm
     */
    protected function setName(?string $name)
    {
    	if(!$name){
    		$name = ($this->editing ? 'editModel-' : 'addModel-') . $this->model::formIdentifier();
    	}
    	$this->name = $name;
        return $this;
    }

    /**
     * Get classes for that form
     * 
     * @return string
     */
    protected function getClasses()
    {
        $classes = theme_config('forms.classes.'.$this->name) ?? theme_config('forms.default-classes');
        $classes .= ' form-'.$this->name.($this->editing ? ' form-editModel' : ' form-addModel');
        return $classes;
    }

    /**
     * @inheritDoc
     */
    public function name()
    {
    	return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function method()
    {
    	return $this->method;
    }

    /**
     * @inheritDoc
     */
    public function url()
    {
    	return $this->url;
    }

    /**
     * @inheritDoc
     */
    public function fields()
    {
    	return $this->fieldList;
    }

    /**
     * @inheritDoc
     */
    public function groups()
    {
    	return ($this->groupList ? $this->groupList : ['default' => $this->getFieldNames()]);
    }

    /**
     * @inheritDoc
     */
    public function attributes()
    {
    	return $this->attributeList;
    }
}