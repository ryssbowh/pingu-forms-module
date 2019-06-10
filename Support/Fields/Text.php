<?php
namespace Pingu\Forms\Support\Fields;

use Illuminate\Database\Eloquent\Builder;
use Pingu\Forms\Support\Field;
use Pingu\Forms\Support\Renderers\Text as TextRenderer;

class Text extends Field
{
	/**
	 * @inheritDoc
	 */
	public static function filterQueryModifier(Builder $query, string $name, $value)
	{
		$query->where($name, 'like', '%'.$value.'%');
	}
}