<?php

namespace Pingu\Forms\Traits;

use Pingu\Forms\Exceptions\FormFieldException;

trait HasFields
{
	protected $fields;

	public function addFields(array $fields)
    {
        foreach($fields as $name => $definition){
            $this->addField($name, $definition);
        }
        return $this;
    }

    public function addField(string $name, array $definition)
    {
        if($this->hasField($name)){
            throw FormFieldException::alreadyDefined($name);
        }
        if(!isset($definition['type'])){
            throw FormFieldException::missingAttribute($name, 'type');
        }
        $type = $definition['type'];
        $field = new $type($name, $definition, $this);
        $this->fields->put($name, $field);
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