<?php
namespace Pingu\Forms\Support\Fields;

use Pingu\Forms\Contracts\HasItemsField;
use Pingu\Forms\Support\Field;
use Pingu\Forms\Support\ItemList;

class Checkboxes extends Field implements HasItemsField
{
	protected $required = ['items'];

	/**
	 * @inheritDoc
	 */
	public function __construct(string $name, array $options = [], array $attributes = [])
	{	
		parent::__construct($name, $options, $attributes);
		$this->option('items', $this->buildItems($this->option('items')));
	}

	/**
	 * @inheritDoc
	 */
	public static function getDefaultType()
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
	public function buildItems($items)
	{
		return new ItemList($items, $this->getName());
	}

	/**
	 * @inheritDoc
	 */
	public function getItems()
	{
		return $this->option('items')->getItems();
	}

	/**
	 * Sets an attribute
	 * 
	 * @param  string $name
	 * @param  mixed $value
	 * @return Field
	 */
	public function attribute(string $name, $value = null)
	{
		$this->option('items')->attribute($name, $value);
		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function setValue($value)
	{
		$this->value = is_array($value) ? $value : [$value];
		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function getDefaultView()
	{
		return 'forms::fields.'.$this->getType();
	}
	
}