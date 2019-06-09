<?php

namespace Pingu\Forms\Support;

use Pingu\Forms\Exceptions\FormActionException;

class ActionBag
{
	public function __construct($actions = [])
	{
		$this->actions = collect();
		foreach($actions as $name => $action){
			$this->add($name, $action);
		}
	}

	public function add(string $name, array $action)
	{
		if($this->has($name)){
			throw FormActionException::alreadyDefined($name);
		}
		if(!isset($action['attributes'])){
			$action['attributes'] = [];
		}
		if(!isset($action['label'])){
			throw FormActionException::labelNotDefined($name);
		}
		if(!isset($action['type'])){
			throw FormActionException::typeNotDefined($name);
		}
		$action['attributes']['class'] = $this->getClass($action['type']);
		$this->actions->put($name, $action);
		return $this;
	}

	protected function getClass(string $type)
	{
		return theme_config('forms.action-classes.'.$type) ?? theme_config('forms.action-default-classes');
	}

	public function get(string $name)
	{
		if(!$this->has($name)){
			throw FormActionException::notDefined($name);
		}
		return $this->actions->get($name);
	}

	public function all()
	{
		return $this->actions->toArray();
	}

	public function toArray()
	{
		return $this->actions->toArray();
	}

	public function has(string $name)
	{
		return $this->actions->has($name);
	}

	public function remove(string $name)
	{
		$this->get($name);
		$this->actions->forget($name);
		return $this;
	}

	public function setType(string $name, string $type)
	{
		$action = $this->get($name);
		$action['type'] = $type;
		$this->actions[$name] = $action;
	}

	public function setAttribute(string $actionName, string $name, $value)
	{
		$action = $this->get($actionName);
		$action['attributes'][$name] = $value;
		$this->actions[$actionName] = $action;
		return $this;
	}

}