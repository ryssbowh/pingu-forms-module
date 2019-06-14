<?php
namespace Pingu\Forms\Support\Fields;

use Pingu\Forms\Contracts\HasItemsField;
use Pingu\Forms\Support\Field;

class Checkboxes extends Field implements HasItemsField
{
	protected $required = ['items'];

	/**
	 * @inheritDoc
	 */
	public function getDefaultType()
	{
		return Boolean::class;
	}

	/**
	 * @inheritDoc
	 */
	public function isMultiple()
	{
		return true;
	}
	
	/**
	 * @inheritDoc
	 */
	public function getItems()
	{
		return $this->option('items'); 
	}

	/**
	 * @inheritDoc
	 */
	public function setValue($value)
	{
		$this->value = is_array($value) ? $value : [$value];
		return $this;
	}
	
}