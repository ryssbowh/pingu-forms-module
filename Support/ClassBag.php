<?php

namespace Pingu\Forms\Support;

class ClassBag
{

	protected $classes = [];

	public function __construct($classes = null)
	{
		if($classes){
			$this->addClasses($classes);
		}
	}

	public function addClasses($classes)
	{
		if(is_string($classes)){
			$this->addFromString($classes);
		}
		elseif(is_array($classes)){
			$this->addMany($classes);
		}
		return $this;
	}

	public function toString()
	{
		return implode(' ', $this->classes);
	}

	public function all()
	{
		return $this->classes;
	}

	public function set(array $classes)
	{
		$this->classes = $classes;
		return $this;
	}

	public function addMany(array $classes)
	{
		foreach($classes as $class){
			$this->add($class);
		}
		return $this;
	}

	public function add(string $class)
	{
		if(!$this->has($class)){
			$this->classes[] = $class;
		}
		return $this;
	}

	public function remove(string $class)
	{
		if($this->has($class)){
			unset($this->classes[array_search($class, $this->classes)]);
		}
		return $this;
	}

	public function has(string $class)
	{
		return in_array($class, $this->classes);
	}

	public function addFromString(string $classes)
	{
		$classes = trim(preg_replace('!\s+!', ' ', $classes));
		foreach(explode(' ', $classes) as $class){
			$this->add($class);
		}
		return $this;
	}
}