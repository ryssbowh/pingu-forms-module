<?php

namespace Pingu\Forms\Traits;

use Pingu\Forms\Exceptions\FormFieldException;
use Pingu\Forms\Exceptions\GroupException;
use Pingu\Forms\Support\Field;
use Pingu\Forms\Traits\HasGroups;

trait HasFields
{
    use HasGroups;
    
	protected $fields;

    /**
     * Adds all fields to the form, but not to any grops yet
     * This is called by the constructor
     * 
     * @param  array  $fields
     * @return Form
     */
	protected function makeFields(array $fields)
	{
		$this->fields = collect();
		$this->_addFields($fields);
        return $this;
	}

    /**
     * Add a field to this form, but not to any group
     * 
     * @param string $name
     * @param array  $definition
     * @throws FormFieldException
     * @return Field
     */
	protected function _addField(string $name, array $definition)
    {
    	if(!isset($definition['field'])){
    		throw FormFieldException::missingDefinition($name, 'field');
    	}
        $definition['options']['type'] = $definition['options']['type'] ?? $definition['field']::getDefaultType();
    	$class = $definition['field'];
    	$field = new $class($name, $definition['options'], $definition['attributes'] ?? []);
        $field->setForm($this);
        $this->fields->put($name, $field);
        return $field;
    }

    /**
     * Add several fields to this forms, but not to any groups
     * 
     * @param array $fields
     * @return Form
     */
	protected function _addFields(array $fields)
    {
        foreach($fields as $field => $definition){
            $this->_addField($field, $definition);
        }
        return $this;
    }

    /**
     * Add fields to this form and to a group
     * 
     * @param array  $fields
     * @param string $group
     * @return Form
     */
    public function addFields(array $fields, $group = 'default')
    {
        foreach($fields as $name => $definition){
            $this->addField($name, $definition);
        }
        return $this;
    }

    /**
     * Adds a field to this form and to a group
     * 
     * @param string $name
     * @param array  $definition
     * @param string $group
     * @return Form
     */
    public function addField(string $name, array $definition, $group = 'default')
    {
        $group = $this->getGroup($group);
        $field = $this->_addField($name, $definition);
        $group->push($name);
        return $field;
    }

    /**
     * Removes fields from this form
     * 
     * @param  array  $fields
     * @return Form
     */
	public function removeFields(array $fields)
	{
		foreach($fields as $name){
			$this->removeField($name, $field);
		}
		return $this;
	}

    /**
     * Removes a field from this form
     * 
     * @param  string $name
     * @return Form
     */
	public function removeField(string $name)
	{
		$this->getField($name);
		$this->fields->forget($name);
		$group = $this->searchFieldGroup($name);
		if($group) $group->forget($name);
		return $this;
	}

    /**
     * Field getter
     * 
     * @param  string $name
     * @throws FormFieldException
     * @return Field
     */
	public function getField(string $name)
	{
		if(!$this->hasField($name)){
			throw FormFieldException::notDefined($name);
		}
		return $this->fields->get($name);
	}

    /**
     * Gets several or all fields from that form
     * @param  array|null $names
     * @return array
     */
	public function getFields(?array $names = null)
	{
		if(is_null($names)) $fields = $this->fields->toArray();
		else $fields = $this->fields->only($names)->toArray();
		return $fields;
	}

    /**
     * Does this form has a field called $name
     * 
     * @param  string  $name
     * @return boolean
     */
	public function hasField(string $name)
	{
		return $this->fields->has($name);
	}

    /**
     * Get all field names for that form
     * 
     * @return array
     */
	public function getFieldNames()
    {
        return $this->fields->keys()->all();
    }

    /**
     * Sets the value for a field in that form
     *
     * @param string $name
     * @param mixed $value
     * @return Form
     */
    public function setFieldValue(string $name, $value)
    {
        $this->getField($name)->setValue($value);
        return $this;
    }

    /**
     * Gets the value for a field in that form
     * 
     * @param  string $name
     * @param  mixed $value
     * @return mixed
     */
    public function getFieldValue(string $name, $value)
    {
        return $this->getField($name)->getValue($value);
    }
}