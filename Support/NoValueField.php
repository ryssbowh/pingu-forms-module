<?php
namespace Pingu\Forms\Support;

use Pingu\Forms\Support\Types\TypeNull;

abstract class NoValueField extends Field
{
	/**
	 * @inheritDoc
	 */
	public function __construct(string $name, array $options = [], array $attributes = [])
	{	
		$options['type'] = TypeNull::class;
		parent::__construct($name, $options, $attributes);
	}

	/**
	 * Get the default type for that field
	 * 
	 * @return string
	 */
	public static function getDefaultType()
	{
		return;
	}

}