<?php

namespace Pingu\Forms\Traits;

use Illuminate\Support\Arr;
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
     * @param array $groups
     * 
     * @return Form
     */
    protected function makeGroups(array $groups)
    {
        $this->groups = collect();
        if ($groups) {
            foreach ($groups as $name => $fields) {
                $this->moveToGroup($fields, $name);
            }
        }
        return $this;
    }

    /**
     * Returns all fields names that are in a group
     * 
     * @return array
     */
    public function allFieldInGroups()
    {
        $out = [];
        foreach ($this->groups as $name => $fields) {
            $out = array_merge($fields->all(), $out);
        }
        return $out;
    }

    /**
     * @return boolean
     */
    public function hasGroups(): bool
    {
        return !$this->groups->isEmpty();
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
        if ($this->hasGroup($name)) {
            throw GroupException::alreadyDefined($name, $this);
        }
        foreach ($fields as $field) {
            if (!$this->hasElement($field)) {
                throw FormFieldException::notDefined($field, $this);
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
        if (is_null($names)) {
            $groups = $this->groups->toArray();
        } else {
            $groups = $this->groups->only($names)->toArray();
        }
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
    public function addToGroup($fields, ?string $groupName)
    {
        $fields = Arr::wrap($fields);
        if (!$this->hasGroup($groupName)) {
            $group = $this->createGroup($groupName);
        }
        foreach ($fields as $field) {
            return $group->push($field);
        }
        return $this;
    }

    /**
     * Move a field to another group
     * 
     * @param  string $fieldName
     * @param  string $groupNameTo
     * @return Form
     */
    public function moveToGroup($fields, string $groupNameTo)
    {
        $fields = Arr::wrap($fields);
        $groupTo = $this->getGroup($groupNameTo, true);
        foreach ($fields as $field) {
            $this->removeFromGroup($field);
            $groupTo->push($field);
        }
        return $this;
    }

    /**
     * Search in which group a field is
     * 
     * @param  string $name
     * @return Collection|false
     */
    public function searchFieldGroup(string $name)
    {
        foreach ($this->groups as $group) {
            if (is_integer($group->search($name))) { 
                return $group;
            }
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
        foreach ($this->groups as $groupName => $group){
            if ($group->search($name) !== false) { 
                return $groupName;
            }
        }
        return false;
    }

    /**
     * Group getter
     * 
     * @param  string $name
     * @return Collection
     */
    public function getGroup(string $name, bool $create = false)
    {
        if (!$this->hasGroup($name)) {
            if ($create) {
                $group = $this->createGroup($name);
            } else {
                throw GroupException::notDefined($name, $this);
            }
        }
        return $this->groups->get($name);
    }

    /**
     * @param  string $name
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
     * Removes a field from a group
     * 
     * @param  string $fieldName
     * @param  string $group
     * @return Form
     */
    public function removeFromGroup(string $field)
    {
        if ($group = $this->searchFieldGroup($field)) {
            $index = $group->search($field);
            $group->forget($index);
        }
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
    public function moveElementUp(string $name, $offset = false)
    {
        $groupName = $this->searchFieldGroupName($name);
        if (!$groupName) {
            throw FormFieldException::notDefined($name, $this);
        }
        $group = $this->groups->get($groupName);
        if (!$offset) {
            return $this->moveElementToTop($name, $group);
        }
        if($offset < 0) {
            return $this->moveElementDown($name, $offset*-1);
        }
        $index = $group->search($name);
        if(($index - $offset) <= 0) {
            return $this->moveElementToTop($name, $group);
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
    public function moveElementDown(string $name, $offset = false)
    {
        $groupName = $this->searchFieldGroupName($name);
        if(!$groupName) {
            throw FormFieldException::notDefined($name, $this);
        }
        $group = $this->groups->get($groupName);
        if(!$offset) {
            return $this->moveElementToBottom($name, $group);
        }
        if($offset < 0) {
            return $this->moveElementUp($name, $offset*-1);
        }
        $index = $group->search($name);
        $size = $group->count();
        if(($index + $offset) >= ($size-1)) {
            return $this->moveElementToBottom($name, $group);
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
     * @param  string     $name
     * @param  Collection $group
     * @return Form
     */
    protected function moveElementToTop(string $name, Collection $group)
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
    protected function moveElementToBottom(string $name, Collection $group)
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
        if(is_null($names)) { $names = $this->getGroupNames();
        }
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