<?php
namespace Pingu\Forms\Fields\Base;

use Pingu\Forms\Renderers\Date;
use Pingu\Forms\Support\Field;

class Datetime extends Field
{
	public $format;
	/**
	 * @inheritDoc
	 */
	public function getDefaultRenderer()
	{
		return Date::class;
	}
}