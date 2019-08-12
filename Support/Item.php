<?php 

namespace Pingu\Forms\Support;

use Illuminate\Support\Collection;

class Item
{	
	/**
	 * Key (value) for this item
	 * @var mixed
	 */
	protected $key;

	/**
	 * label for this item
	 * @var string
	 */
	protected $label;

	/**
	 * attribute collection
	 * @var Collection
	 */
	public $attributes;

	public function __construct(string $key, $label)
	{	
		$this->attributes = collect();
		$this->key = $key;
		$this->label = $label;
	}

	/**
     * Sets/gets an attribute
     * 
     * @param  string $name
     * @param  mixed $value
     * @return Form|mixed
     */
	public function attribute(string $name, $value = null)
	{
		if(!is_null($value)){
			$this->attributes->put($name, $value);
			return $this;
		}
		return $this->attributes->get($name);
	}

	/**
	 * get label
	 * 
	 * @return string
	 */
	public function getLabel()
	{
		return $this->label;
	}

	/**
	 * Get key (value)
	 * 
	 * @return mixed
	 */
	public function getKey()
	{
		return $this->key;
	}

	/**
	 * Get attributes as array
	 * 
	 * @return array
	 */
	public function getAttributes()
	{
		return $this->attributes->toArray();
	}

	/**
	 * Set the id attribute according to a field name
	 * 
	 * @param string $fieldName
	 * @return  Item
	 */
	public function setId(string $fieldName)
	{
		$this->attribute('id', 'field-'.$fieldName.'-'.$this->getKey());
		return $this;
	}

	/**
	 * Get the id attribute
	 * 
	 * @return string
	 */
	public function getId()
	{
		return $this->attribute('id');
	}

}