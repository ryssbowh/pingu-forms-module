<?php
namespace Pingu\Forms\Exceptions;

class FieldNotInGroup extends \Exception{

	public function __construct($name, $group)
	{
		parent::__construct("$name is not a a field in $group");
	}

}