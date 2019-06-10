<?php

namespace Pingu\Forms\Traits;

use Pingu\Forms\Exceptions\FormFieldException;
use Pingu\Forms\Exceptions\GroupException;
use Pingu\Forms\Traits\HasGroups;

trait HasFields
{
    use HasGroups;
    
	protected $fields;

	protected function makeFields($fields)
	{
		$this->fields = collect();
		foreach($fields as $name => $definition){
			$this->_addField($name, $definition);
		}
	}

	protected function _addField(string $name, array $definition)
    {
        if(!isset($definition['type'])){
            throw FormFieldException::missingAttribute($name, 'type');
        }
        if(!isset($definition['renderer'])){
            throw FormFieldException::missingAttribute($name, 'renderer');
        }
        $type = $definition['type'];
        unset($definition['type']);
        $field = new $type($name, $definition, $this);
        $this->fields->put($name, $field);
        return $field;
    }

	protected function _addFields(array $fields)
    {
        foreach($fields as $name => $definition){
            $this->_addField($name, $definition);
        }
        return $this;
    }

    public function addFields(array $fields, $group = 'default')
    {
        foreach($fields as $name => $definition){
            $this->addField($name, $definition, $group);
        }
        return $this;
    }

    public function addField(string $name, array $definition, $group = 'default')
    {
        if($this->hasField($name)){
            throw FormFieldException::alreadyDefined($name);
        }
        if(!isset($definition['type'])){
            throw FormFieldException::missingAttribute($name, 'type');
        }
        $group = $this->getGroup($group);
        $type = $definition['type'];
        $field = new $type($name, $definition, $this);
        $this->fields->put($name, $field);
        $group->push($name);
        return $field;
    }

	public function removeFields(array $fields)
	{
		foreach($fields as $name){
			$this->removeField($name, $field);
		}
		return $this;
	}

	public function removeField(string $name)
	{
		$this->getField($name);
		$this->fields->forget($name);
		$group = $this->searchFieldGroup($name);
		if($group) $group->forget($name);
		return $this;
	}

	public function getField(string $name)
	{
		if(!$this->hasField($name)){
			throw FormFieldException::notDefined($name);
		}
		return $this->fields->get($name);
	}

	public function getFields(?array $names = null)
	{
		if(is_null($names)) $fields = $this->fields->toArray();
		else $fields = $this->fields->only($names)->toArray();
		return $fields;
	}

	public function hasField(string $name)
	{
		return $this->fields->has($name);
	}

	public function getFieldNames()
    {
        return $this->fields->keys()->all();
    }

    public function setFieldValue(string $name, $value)
    {
        $this->getField($name)->setValue($value);
        return $this;
    }

    public function getFieldValue(string $name, $value)
    {
        return $this->getField($name)->getValue($value);
    }
}