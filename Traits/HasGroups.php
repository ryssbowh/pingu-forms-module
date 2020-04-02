<?php

namespace Pingu\Forms\Traits;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Pingu\Forms\Exceptions\FormException;
use Pingu\Forms\Exceptions\FormFieldException;
use Pingu\Forms\Exceptions\GroupException;
use Pingu\Forms\Support\FormGroup;

trait HasGroups
{
    /**
     * @var Collection
     */
    protected $groups;

    /**
     * Creates all groups for that form. When this is called fields must already be defined
     * 
     * @param array $groups
     * 
     * @return $this
     */
    protected function makeGroups(array $groups)
    {
        $this->groups = collect();
        if ($groups) {
            foreach ($groups as $name => $fields) {
                $this->createGroup($name, $fields);
            }
        }
        return $this;
    }

    /**
     * Creates a new group
     * 
     * @param string $name
     * @param array  $fields
     * 
     * @return $this
     */
    public function createGroup(string $name, array $fields)
    {
        $name = strtolower($name);
        if ($this->hasGroup($name)) {
            throw GroupException::alreadyDefined($name, $this);
        }
        $group = new FormGroup($name, $fields, $this);
        $this->groups->put($name, $group);
        return $this;
    }

    /**
     * Returns all fields names that are in a group
     * 
     * @return array
     */
    public function allFieldInGroups(): array
    {
        $out = [];
        foreach ($this->groups as $name => $group) {
            $out = array_merge($group->getFields(), $out);
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
     * Gets some or all groups as arrays
     * 
     * @param array|null $names
     * 
     * @return array
     */
    public function getGroups(?array $names = null): array
    {
        if (is_null($names)) {
            $groups = $this->groups->toArray();
        } else {
            $groups = $this->groups->only($names)->toArray();
        }
        return $groups;
    }

    /**
     * @param string $name
     * 
     * @return $this
     */
    public function removeGroup(string $name)
    {
        $this->getGroup($name);
        $this->groups->forget($name);
        return $this;
    }

    /**
     * @return integer
     */
    public function countGroups(): int
    {
        return $this->groups->count();
    }

    /**
     * Add field(s) to a group
     * 
     * @param string|array $fields
     * @param string|null  $groupName
     *
     * @return $this
     */
    public function addToGroup($fields, string $groupName)
    {
        $fields = Arr::wrap($fields);
        $group = $this->getGroup($groupName);
        foreach ($fields as $field) {
            $group->addField($field);
        }
        return $this;
    }

    /**
     * Move a field to another group
     * 
     * @param string $fieldName
     * @param string $groupNameTo
     * 
     * @return $this
     */
    public function moveToGroup($fields, string $groupNameTo)
    {
        $fields = Arr::wrap($fields);
        $groupTo = $this->getGroup($groupNameTo);
        foreach ($fields as $field) {
            $this->removeFromGroup($field);
            $groupTo->addField($field);
        }
        return $this;
    }

    /**
     * Search in which group a field is
     * 
     * @param string $name
     * 
     * @return FormGroup|false
     */
    public function searchFieldGroup(string $name)
    {
        foreach ($this->groups as $group) {
            if ($group->hasField($name)) { 
                return $group;
            }
        }
        return false;
    }

    /**
     * Search in which group a field is and return its name.
     * 
     * @param string $name
     * 
     * @return string|false
     */
    public function searchFieldGroupName(string $name)
    {
        foreach ($this->groups as $groupName => $group) {
            if ($group->hasField($name)) { 
                return $groupName;
            }
        }
        return false;
    }

    /**
     * Group getter
     * 
     * @param string $name
     * 
     * @return FormGroup
     */
    public function getGroup(string $name, bool $create = false): FormGroup
    {
        if (!$this->hasGroup($name)) {
            if ($create) {
                $group = $this->createGroup($name, []);
            } else {
                throw GroupException::notDefined($name, $this);
            }
        }
        return $this->groups->get($name);
    }

    /**
     * @param string $name
     * 
     * @return boolean
     */
    public function hasGroup(string $name): bool
    {
        return $this->groups->has($name);
    }

    /**
     * Does a group has a field
     * 
     * @param string $group
     * @param string $name
     * 
     * @return bool
     */
    public function groupHasField(string $group, string $name): bool
    {
        return $this->getGroup($group)->hasField($name);
    }

    /**
     * Removes a field from a group
     * 
     * @param string $fieldName
     * @param string $group
     * 
     * @return $this
     */
    public function removeFromGroup(string $field)
    {
        if ($group = $this->searchFieldGroup($field)) {
            $group->removeField($index);
        }
        return $this;
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