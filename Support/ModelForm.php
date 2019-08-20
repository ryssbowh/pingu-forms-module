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

    public function __construct(
        array $url, 
        string $method, 
        FormableContract $model, 
        bool $editing, 
        $fields = [], 
        ?string $name = null, 
        array $attributes = [], 
        array $groups = []
    )
    {
        $this->editing = $editing;
        $this->model = $model;
        $this->fieldList = $fields;
    	if(!$fields){
    		$this->fieldList = $this->editing ? $model->getEditFormFields() : $model->getAddFormFields();
    	}
        $this->attributeList = $attributes;
    	$this->name = ($this->editing ? 'edit-' : 'add-').$model::formIdentifier();
    	$this->url = $url;
    	$this->method = $method;
    	$this->groupList = $groups;
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
    	$this->name = $name;
        return $this;
    }

    /**
     * Get classes for that form
     * 
     * @return string
     */
    protected function getDefaultClasses()
    {
        $classes = theme_config('forms.classes.'.$this->name) ?? theme_config('forms.default-classes');
        $classes .= ' form form-'.$this->name.($this->editing ? ' form-editModel' : ' form-addModel');
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