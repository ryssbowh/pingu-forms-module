<?php
namespace Pingu\Forms\Fields;

use Illuminate\Database\Eloquent\Builder;
use Pingu\Forms\Renderers\SingleCheckbox;

class Boolean extends Field
{
	public function __construct(string $name, array $options = [])
	{
		$options['renderer'] = $options['renderer'] ?? SingleCheckbox::class;
		parent::__construct($name, $options);
	}

	public static function fieldQueryModifier(Builder $query, string $name, $value)
	{
		$query->where($name, '=', (int)$value);
	}
}