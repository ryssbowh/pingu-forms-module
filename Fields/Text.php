<?php
namespace Pingu\Forms\Fields;

use Illuminate\Database\Eloquent\Builder;
use Pingu\Forms\Renderers\Text as TextRenderer;

class Text extends Field
{

	public function __construct(string $name, array $options = [])
	{
		$options['renderer'] = $options['renderer'] ?? TextRenderer::class;
		parent::__construct($name, $options);
	}

	public static function filterQueryModifier(Builder $query, string $name, $value)
	{
		$query->where($name, 'like', '%'.$value.'%');
	}
}