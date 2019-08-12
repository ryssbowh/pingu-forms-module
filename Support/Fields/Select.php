<?php
namespace Pingu\Forms\Support\Fields;

use Pingu\Forms\Contracts\HasItemsField;
use Pingu\Forms\Support\Field;
use Pingu\Forms\Support\Types\Text;

class Select extends Field implements HasItemsField
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

	public function getName()
	{
		if($this->isMultiple()){
			return $this->name.'[]';
		}
		return $this->name;
	}

	/**
	 * @inheritDoc
	 */
	public function buildItems($items)
	{
		return $items;
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
	public function isMultiple()
	{
		return $this->attribute('multiple') ?? false;
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