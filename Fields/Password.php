<?php
namespace Pingu\Forms\Fields;

use Hash;
use Pingu\Core\Entities\BaseModel;
use Pingu\Forms\Renderers\Password as PasswordRenderer;

class Password extends Field
{
	public function __construct(string $name, array $options = [])
	{
		$options['renderer'] = $options['renderer'] ?? PasswordRenderer::class;
		parent::__construct($name, $options);
	}

	/**
	 * Set the value for a model for this type of field
	 * @param BaseModel $model
	 * @param string    $field
	 * @param mixed    $value
	 */
	public static function setModelValue(BaseModel $model, string $field, $value)
	{
		$model->$field = Hash::make($value);
	}
}