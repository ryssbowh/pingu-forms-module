<?php
namespace Pingu\Forms\Exceptions;

class GroupNotDefined extends \Exception{

	public function __construct($name)
	{
		parent::__construct("$name is not a group in this form");
	}

}