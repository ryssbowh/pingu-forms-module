<?php

namespace Pingu\Forms\Support;

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

    public function __construct(array $url, string $method, $model, $fields, ?string $name = null, array $attributes = [], array $actions = [], array $groups = [])
    {
    	$this->model = $model;
    	$this->setName($name);
    	$this->url = $url;
    	$this->method = $method;
    	$this->fieldList = $fields;
    	$this->actionList = $actions;
    	$this->groupList = $groups;
    	$this->attributeList = $attributes;
    	parent::__construct();
    }

    protected function setName(?string $name)
    {
    	if(!$name){
    		
    	}
    }

    protected function makeFields()
    {
        $this->addModelFields($this->fieldList, $this->model);
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
    	return $this->fieldsList;
    }

    public function actions()
    {
    	return $this->actionList;
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