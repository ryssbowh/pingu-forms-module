<?php
namespace Modules\Forms\Fields;

use Illuminate\Database\Eloquent\Builder;
use Modules\Forms\Renderers\Text as TextRenderer;

class Text extends Field
{

	public function __construct(string $name, array $options = [])
	{
		$options['renderer'] = $options['renderer'] ?? TextRenderer::class;
		parent::__construct($name, $options);
	}

	public static function fieldQueryModifier(Builder $query, string $name, $value)
	{
		$query->where($name, 'like', '%'.$value.'%');
	}
}