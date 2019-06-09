<?php

namespace Pingu\Forms\Support;

class AttributeBag
{
	protected $attributes;

	public function __construct($attributes = null)
	{
		$this->attributes = collect();
		if($attributes) $this->addMany($attributes);
	}

	protected function set(array $attributes)
	{
		$this->attributes = collect($attributes);
	}

	public function addMany(array $attributes)
	{
		foreach($attributes as $name => $attribute){
			$this->add($name, $attribute);
		}
		return $this;
	}

	public function add(string $name, $value)
	{
		$this->attributes->put($name, $value);
		return $this;
	}

	public function remove(string $name)
	{
		$this->attributes->forget($name);
		return $this;
	}

	public function removeMany(array $attributes)
	{
		foreach($attributes as $name){
			$this->remove($name);
		}
		return $this;
	}

	public function all()
	{
		return $this->attributes->toArray();
	}

	public function toArray()
	{
		return $this->attributes->toArray();
	}

	public function get(string $name)
	{
		return $this->attributes->get($name);
	}

	public function has(string $name)
	{
		return $this->attributes->has($name);
	}
}