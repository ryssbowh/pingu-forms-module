<?php

namespace Pingu\Forms\Traits;

use Illuminate\Support\Collection;
use Pingu\Forms\Exceptions\FormException;
use Pingu\Forms\Exceptions\FormFieldException;
use Pingu\Forms\Exceptions\GroupException;

trait HasGroups
{
	protected $groups;

	/**
	 * Creates all groups for that form. When this is called fields must already be defined
	 * 
	 * @param  array  $groups
	 * @return Form
	 */
	private function makeGroups(array $groups)
	{
		if(!$groups){
			throw FormException::noGroups(class_basename($this));
		}
		$this->groups = collect();
		foreach ($groups as $name => $fields) {
			$this->createGroup($name, $fields);
		}
		return $this;
	}

	/**
	 * Creates a new group
	 * 
	 * @param  string $name
	 * @param  array  $fields
	 * @return Collection
	 */
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

	/**
	 * Gets some or all groups as arrays
	 * 
	 * @param  array|null $names
	 * @return array
	 */
	public function getGroups(?array $names = null)
	{
		if(is_null($names)) $groups = $this->groups->toArray();
		else $groups = $this->groups->only($names)->toArray();
		return $groups;
	}

	/**
	 * @param  string $name
	 * @return Collection
	 */
	public function removeGroup(string $name)
	{
		$this->getGroup($name);
		return $this->groups->forget($name);
	}

	/**
	 * @return integer
	 */
	public function countGroups()
	{
		return $this->groups->count();
	}

	/**
	 * Adds a field to a group, removing it from the group the 
	 * field was already in.
	 * 
	 * @param string      $field
	 * @param string|null $groupName
	 */
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

	/**
	 * Gets or creates the 'default' group
	 * @return Collection
	 */
	public function getDefaultGroup()
	{
		if(!$this->hasGroup('default')){
			return $this->createGroup('default');
		}
		return $this->group->get('default');
	}

	/**
	 * Search in which group a field is
	 * 
	 * @param  string $name
	 * @return Collection|false
	 */
	public function searchFieldGroup(string $name)
	{
		foreach($this->groups as $group){
			if($group->search($name)) return $group;
		}
		return false;
	}

	/**
	 * Search in which group a field is and return its name.
	 * 
	 * @param  string $name
	 * @return string|false
	 */
	public function searchFieldGroupName(string $name)
	{
		foreach($this->groups as $groupName => $group){
			if($group->search($name) !== false) return $groupName;
		}
		return false;
	}

	/**
	 * Group getter
	 * 
	 * @param  string $name
	 * @return Collection
	 */
	public function getGroup(string $name)
	{
		if(!$this->hasGroup($name)){
			throw GroupException::notDefined($name);
		}
		return $this->groups->get($name);
	}

	/**
	 * @param  string  $name
	 * @return boolean
	 */
	public function hasGroup(string $name)
	{
		return $this->groups->has($name);
	}

	/**
	 * Does a group has a field
	 * 
	 * @param  string $group
	 * @param  string $name
	 * @return bool
	 */
	public function groupHasField(string $group, string $name)
	{
		return $this->getGroup($group)->contains($name);
	}

	/**
	 * Move a field to another group
	 * 
	 * @param  string $fieldName
	 * @param  string $groupNameTo
	 * @return Form
	 */
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

	/**
	 * Removes a field from a group
	 * 
	 * @param  string $fieldName
	 * @param  string $group
	 * @return Form
	 */
	public function removeFieldFromGroup(string $fieldName, ?string $group = 'default')
	{
		$group = $this->getGroup($groupName);
		$group->forget($fieldName);
		return $this;
	}

	/**
	 * Moves a field up in its group.
	 * Offset can be false, in which case the field will be moved at the top.
	 * If the offset is negative, field will be moved down.
	 * 
	 * @param  string  $name
	 * @param  boolean $offset
	 * @return Form
	 */
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

	/**
	 * Moves a field down in its group.
	 * Offset can be false, in which case the field will be moved at the bottom.
	 * If the offset is negative, field will be moved up.
	 * 
	 * @param  string  $name
	 * @param  boolean $offset
	 * @return Form
	 */
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

	/**
	 * Moves a field a the top of its group
	 * 
	 * @param  string $name
	 * @param  Collection $group
	 * @return Form
	 */
	protected function moveFieldToTop(string $name, Collection $group)
	{
		$index = $group->search($name);
		$group->splice($index, 1);
		$group->prepend($name);
		return $this;
	}

	/**
	 * Moves a field at the bottom of its group
	 * 
	 * @param  string     $name
	 * @param  Collection $group
	 * @return Form
	 */
	protected function moveFieldToBottom(string $name, Collection $group)
	{
		$index = $group->search($name);
		$group->splice($index, 1);
		$group->push($name);
		return $this;
	}

	/**
	 * Builds groups as arrays for rendering
	 * 
	 * @param  array|null $names
	 * @return array
	 */
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

	/**
	 * Gets all group names
	 * 
	 * @return array
	 */
	public function getGroupNames()
	{
		return $this->groups->keys()->all();
	}
}