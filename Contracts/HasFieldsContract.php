<?php 
namespace Pingu\Forms\Contracts;

interface HasFieldsContract
{
	public function removeField(string $name);

	public function getField(string $name);

	public function hasField(string $name);

	public function addField(string $name, array $definition);

	public function addFields(array $fields);
}