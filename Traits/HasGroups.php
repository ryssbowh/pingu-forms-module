<?php

namespace Pingu\Forms\Traits;

use Pingu\Forms\Exceptions\FormFieldException;
use Pingu\Forms\Exceptions\GroupException;

trait HasGroups
{
	protected $groups;

	private function makeGroups(array $groups)
	{
		$this->groups = collect();
		foreach ($groups as $name => $fields) {
			$this->createGroup($name, $fields);
		}
		return $this;
	}

	private function createGroup(string $name, $fields = [])
	{
		foreach($fields as $field){
			if(!$this->hasField($field)){
				throw FormFieldException::notDefined($field);
			}
		}
		$group = collect($fields);
		$this->groups->put($name, $group);
		return $group;
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

	public function countGroups()
	{
		return $this->groups->count();
	}

    public function addFieldToGroup(string $field, ?string $groupName = null)
	{
		if(is_null($groupName)){
			$group = $this->getDefaultGroup();
		}
		elseif(!$group = $this->hasGroup($groupName)){
			$group = $this->createGroup($groupName);
		}
		if(!$this->groupHasField($groupName, $field)){
			$groupFrom = $this->searchFieldGroup($field);
			$groupFrom->forget($field);
			$group->push($field);
		}
		return $this;
	}

	public function getDefaultGroup()
	{
		if(!$this->hasGroup('default')){
			return $this->createGroup('default');
		}
		return $this->group->get('default');
	}

	public function searchFieldGroup(string $name)
	{
		foreach($this->groups as $group){
			if($group->search($name)) return $group;
		}
		return false;
	}

	public function searchFieldGroupName(string $name)
	{
		foreach($this->groups as $groupName => $group){
			if($group->search($name) !== false) return $groupName;
		}
		return false;
	}

	public function getGroup(string $name)
	{
		if(!$this->hasGroup($name)){
			throw GroupException::notDefined($name);
		}
		return $this->groups->get($name);
	}

	public function hasGroup(string $name)
	{
		return $this->groups->has($name);
	}

	public function groupHasField(string $group, string $name)
	{
		return $this->getGroup($group)->contains($name);
	}

	public function moveFieldToGroup(string $fieldName, string $groupNameTo)
	{
		$groupFrom = $this->searchFieldGroup($fieldName);
		if(!$groupFrom){
			throw FormFieldException::notDefined($fieldName);
		}
		$groupTo = $this->getGroup($groupNameTo);
		$groupFrom->forget($fieldName);
		$groupTo->push($fieldName);
		return $this;
	}

	public function removeFieldFromGroup(string $fieldName, ?string $group = 'default')
	{
		$group = $this->getGroup($groupName);
		$group->forget($fieldName);
		return $this;
	}

	public function moveFieldUp(string $name, $offset = false)
	{
		$groupName = $this->searchFieldGroupName($name);
		if(!$groupName){
			throw FormFieldException::notDefined($name);
		}
		$group = $this->groups->get($groupName);
		if(!$offset){
			return $this->moveFieldToTop($name, $group);
		}
		if($offset < 0){
			return $this->moveFieldDown($name, $offset*-1);
		}
		$index = $group->search($name);
		if(($index - $offset) <= 0){
			return $this->moveFieldToTop($name, $group);
		}
		$replace = [$name, $group->get($index-$offset)];
		$group = $group->forget($index)->values();
		$group->splice($index-$offset, 1, $replace);
		$this->groups->put($groupName, $group);
		return $this;
	}

	public function moveFieldDown(string $name, $offset = false)
	{
		$groupName = $this->searchFieldGroupName($name);
		if(!$groupName){
			throw FormFieldException::notDefined($name);
		}
		$group = $this->groups->get($groupName);
		if(!$offset){
			return $this->moveFieldToBottom($name, $group);
		}
		if($offset < 0){
			return $this->moveFieldUp($name, $offset*-1);
		}
		$index = $group->search($name);
		$size = $group->count();
		if(($index + $offset) >= ($size-1)){
			return $this->moveFieldToBottom($name, $group);
		}
		$replace = [$group->get($index+$offset), $name];
		$group = $group->forget($index)->values();
		$group->splice($index+$offset-1, 1, $replace);
		$this->groups->put($groupName, $group);
		return $this;
	}

	protected function moveFieldToTop(string $name, $group)
	{
		$index = $group->search($name);
		$group->splice($index, 1);
		$group->prepend($name);
		return $this;
	}

	protected function moveFieldToBottom(string $name, $group)
	{
		$index = $group->search($name);
		$group->splice($index, 1);
		$group->push($name);
		return $this;
	}

	public function buildGroups($names = null)
	{
		if(is_null($names)) $names = $this->getGroupNames();
		$groups = $this->getGroups($names);
		$out = [];
        foreach($groups as $name => $fields){
        	$out[$name] = array_merge(array_flip($fields), $this->getFields($fields));
        }
        return $out;
	}

	public function getGroupNames()
	{
		return $this->groups->keys()->all();
	}
}