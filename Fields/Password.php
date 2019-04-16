<?php
namespace Modules\Forms\Fields;

use Modules\Forms\Renderers\Password as PasswordRenderer;

class Password extends Field
{
	public function __construct(string $name, array $options = [])
	{
		$options['renderer'] = $options['renderer'] ?? PasswordRenderer::class;
		parent::__construct($name, $options);
	}
}