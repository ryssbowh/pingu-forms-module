<?php
namespace Pingu\Forms\Renderers;

use FormFacade;

abstract class FieldRenderer
{
	protected $type, $view, $options;
	
	public function __construct($options)
	{	
		$options['type'] = strtolower(classname($this));
		$this->view = $options['view'] ?? 'forms::fields.'.$options['type'];
		$options['label'] = $options['label'] ?? ucfirst($options['name']);
		$options['label'] = FormFacade::label($options['name'], $options['label']);
		$options['attributes'] = $options['attributes'] ?? [];
		$options['attributes']['class'] = $options['attributes']['class'] ?? config('forms.input-classes.'.$options['type']) ?? '';
		$this->options = $options;
	}

	/**
	 * Renders this field
	 * @return string
	 */
	public function render(){
		echo view($this->view, $this->options)->render();
	}

	public function __toString()
	{
		$this->render();
	}
}