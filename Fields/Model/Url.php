<?php
namespace Pingu\Forms\Fields\Model;

use Pingu\Forms\Contracts\ModelFieldContract;
use Pingu\Forms\Fields\Base\Url as Base;
use Pingu\Forms\Traits\ModelField;

class Url extends Base implements ModelFieldContract
{
	use ModelField;
}