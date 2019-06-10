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
    public $editing = false;

    public function __construct(array $url, string $method, FormableContract $model, $fields = [], ?string $name = null, array $attributes = [], array $groups = [])
    {
    	$this->model = $model;
    	if($model->exists()){
    		$this->editing = true;
    	}
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

    protected function setName(?string $name)
    {
    	if(!$name){
    		$name = ($this->editing ? 'editModel-' : 'addModel-') . $this->model::formIdentifier());
    	}
    	$this->name = $name;
    }

    protected function makeFields()
    {
        $this->fields = collect();
        $this->_addModelFields($this->fieldList, $this->model);
    }

    public function name()
    {
    	return $this->name;
    }

    public function method()
    {
    	return $this->method;
    }

    public function url()
    {
    	return $this->url;
    }

    public function fields()
    {
    	return $this->fieldList;
    }

    public function groups()
    {
    	return $this->groupList;
    }

    public function attributes()
    {
    	return $this->attributeList;
    }
}