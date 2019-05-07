<?php
namespace Pingu\Forms\Fields;

use Pingu\Forms\Renderers\Email as EmailRenderer;

class Email extends Text
{
	public function __construct(string $name, array $options = [])
	{
		$options['renderer'] = $options['renderer'] ?? EmailRenderer::class;
		parent::__construct($name, $options);
	}
}