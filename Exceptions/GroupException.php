<?php
namespace Pingu\Forms\Exceptions;

class GroupException extends \Exception{

	public static function alreadyDefined($name)
	{
		return new static("Group $name is already defined in this form");
	}

	public static function notDefined($name)
	{
		return new static("Group $name is not defined in this form");
	}

	public static function hasField($name, $group)
	{
		return new static("Field $name is already in $group");
	}

}