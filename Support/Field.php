<?php

namespace Pingu\Forms\Support;

use Pingu\Core\Traits\HasViewSuggestions;
use Pingu\Forms\Contracts\FieldContract;
use Pingu\Forms\Traits\Field as FieldTrait;

abstract class Field implements FieldContract
{
	use FieldTrait, HasViewSuggestions;

	public $required = false;
}