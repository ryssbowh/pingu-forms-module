<?php 
namespace Pingu\Forms\Contracts;

interface HasGroupsContract
{
	public function addGroup(string $name, string $group);

	public function getGroup(string $name);

	public function removeGroup(string $name);

	public function hasGroup(string $name);

	public function addFieldToGroup(string $field, string $groupName);

	public function moveFieldToGroup(string $fieldName, string $groupNameTo);

	public function removeFieldFromGroup(string $fieldName, string $groupName);

	public function isFieldInGroup(string $fieldName, string $groupName);

	public function searchFieldGroup(string $fieldName);

	public function getDefaultGroup();

	public function getDefaultGroupName();

	public function makeGroups();

	public function countGroups();
}