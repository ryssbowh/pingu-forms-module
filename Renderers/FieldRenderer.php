<?php
namespace Pingu\Forms\Renderers;

use FormFacade;
use Pingu\Forms\Fields\Field;

abstract class FieldRenderer
{
	protected $view, $options, $field;
	
	public function __construct(Field $field)
	{	
		$this->field = $field;
		$options = $field->getOptions();
		$options['type'] = strtolower(class_basename($this));
		$this->view = $options['view'] ?? 'forms::fields.'.$options['type'];
		$options['label'] = $options['label'] ?? ucfirst($options['name']);
		$options['rendererAttributes']['class'] = $this->getRendererClasses($options);
		$options['fieldClasses'] = $this->getFieldClasses($options);
		$options['fieldInnerClasses'] = $this->getInnerFieldClasses($options);
		$options['labelClasses'] = $this->getLabelClasses($options);
		$this->options = $options;
	}

	protected function getLabelClasses(array $options)
	{	
		return $options['labelClasses'] ?? theme_config('forms.label-classes.'.$options['type']) ?? theme_config('forms.label-default-classes') ?? '';
	}

	protected function getFieldClasses(array $options)
	{	
		return $options['fieldClasses'] ?? theme_config('forms.field-classes.'.$options['type']) ?? theme_config('forms.field-default-classes') ?? '';
	}

	protected function getInnerFieldClasses(array $options)
	{	
		return $options['fieldInnerClasses'] ?? theme_config('forms.field-inner-classes.'.$options['type']) ?? theme_config('forms.field-inner-default-classes') ?? '';
	}

	protected function getRendererClasses(array $options)
	{	
		return $options['rendererAttributes']['class'] ?? theme_config('forms.renderer-classes.'.$options['type'])  ?? theme_config('forms.renderer-default-classes') ?? '';
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