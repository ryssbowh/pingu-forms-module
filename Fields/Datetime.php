<?php
namespace Modules\Forms\Fields;

use Modules\Forms\Renderers\Date;

class Datetime extends Text
{
	public function __construct(string $name, array $options = [])
	{
		$options['renderer'] = $options['renderer'] ?? Date::class;
		parent::__construct($name, $options);
	}
}