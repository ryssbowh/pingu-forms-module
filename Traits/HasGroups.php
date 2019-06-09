<?php

namespace Pingu\Forms\Traits;

use Pingu\Forms\Contracts\FormFieldContract;
use Pingu\Forms\Contracts\FormGroupContract;
use Pingu\Forms\Exceptions\GroupException;

trait HasGroups
{
	protected $groups;

	protected function makeGroups(array $fields, $groups = null)
	{
		if($groups){
			$this->groups = collect($groups);
		}
		else{
			$this->groups = collect(['default' => $fields]);
		}
	}

	public function getDefaultGroupName()
	{
		return 'default';
	}

	public function getDefaultGroup()
	{
		return $this->getGroup($this->getDefaultGroupName());
	}

	public function countGroups()
	{
		return $this->groups->count();
	}

	public function addGroup(string $name, array $fields = [])
	{
		if($this->hasGroup($name)){
			throw GroupException::alreadyDefined($name);
		}
		$this->groups->put($name, collect($fields));
	}

	public function getGroup(string $name)
	{
		if(!$this->hasGroup($name)){
			throw GroupException::notDefined($name);
		}
		return $this->groups->get($name);
	}

	public function getGroups(?array $names = null)
	{
		if(is_null($names)) $groups = $this->groups->toArray();
		else $groups = $this->groups->only($names)->toArray();
		return $groups;
	}

	public function removeGroup(string $name)
	{
		$this->getGroup($name);
		return $this->groups->forget($name);
	}

	public function hasGroup(string $name)
	{
		return $this->groups->has($name);
	}

	public function addFieldToGroup(string $field, ?string $groupName = null)
	{
		if(is_null($groupName)){
			$groupName = $this->getDefaultGroupName();
		}
		if($this->groupHasField($groupName, $field)){
			throw GroupException::hasField($groupName, $field);
		}
		$group = $this->getGroup($groupName)->add($field);
		return $this;
	}

	public function groupHasField(string $group, string $name)
	{
		return $this->getGroup($group)->contains($name);
	}

	public function moveFieldToGroup(string $fieldName, string $groupNameTo)
	{
		$groupFrom = $this->searchFieldGroup($fieldName);
		if(!$groupFrom){
			throw GroupException::notDefined($fieldName);
		}
		$groupTo = $this->getGroup($groupNameTo);
		$groupFrom->forget($fieldName);
		$groupTo->add($fieldName);
		return $this;
	}

	public function searchFieldGroup(string $name)
	{
		foreach($this->groups as $group){
			if($group->contains($name)) return $group;
		}
	}

	public function removeFieldFromGroup(string $fieldName, ?string $groupName = null)
	{
		if(is_null($groupName)){
			$group = $this->searchFieldGroup($fieldName);
		}
		else{
			$group = $this->getGroup($groupName);
		}
		$group->forget($fieldName);
		return $this;
	}

	public function isFieldInGroup(string $fieldName, string $groupName)
	{
		return $this->getGroup($groupName)->contains($fieldName);
	}
}