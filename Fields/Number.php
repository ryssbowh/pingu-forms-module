<?php
namespace Modules\Forms\Fields;

use Modules\Forms\Renderers\Number as NumberRenderer;

class Number extends Field
{
	public function __construct(string $name, array $options = [])
	{
		$options['renderer'] = $options['renderer'] ?? NumberRenderer::class;
		parent::__construct($name, $options);
	}
}