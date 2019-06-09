<?php
namespace Pingu\Forms\Fields\Model;

use Pingu\Forms\Contracts\ModelFieldContract;
use Pingu\Forms\Fields\Base\Number as Base;
use Pingu\Forms\Traits\ModelField;

class Number extends Base implements ModelFieldContract
{
	use ModelField;
}