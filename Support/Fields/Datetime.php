<?php
namespace Pingu\Forms\Support\Fields;

use Pingu\Forms\Support\Field;
use Pingu\Forms\Support\Types\Datetime as DatetimeType;

class Datetime extends Field
{	
	public function __construct(string $name, array $options = [], array $attributes = [])
	{
		$options['format'] = $options['format'] ?? 'YYYY-MM-DD HH:mm:ss';
		parent::__construct($name, $options, $attributes);
	}

	/**
	 * @inheritDoc
	 */
	public static function getDefaultType()
	{
		return DatetimeType::class;
	}

	/**
	 * @inheritDoc
	 */
	public function getDefaultView()
	{
		return 'forms::fields.'.$this->getType();
	}
}