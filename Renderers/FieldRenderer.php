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
		$options['rendererAttributes']['required'] = $options['required'] ?? false;
		$options['fieldClasses'] = $this->getFieldClasses($options);
		$options['fieldInnerClasses'] = $this->getInnerFieldClasses($options);
		$options['labelClasses'] = $this->getLabelClasses($options);
		$this->options = $options;
	}

	protected function getLabelClasses(array $options)
	{	
		$classes = theme_config('forms.label-classes.'.$options['type']) ?? theme_config('forms.label-default-classes') ?? '';
		return isset($options['labelClasses']) ? $classes.' '.$options['labelClasses'] : $classes;
	}

	protected function getFieldClasses(array $options)
	{	
		$classes = theme_config('forms.field-classes.'.$options['type']) ?? theme_config('forms.field-default-classes') ?? '';
		return isset($options['fieldClasses']) ? $classes.' '.$options['fieldClasses'] : $classes;
	}

	protected function getInnerFieldClasses(array $options)
	{	
		$classes = theme_config('forms.field-inner-classes.'.$options['type']) ?? theme_config('forms.field-inner-default-classes') ?? '';
		return isset($options['fieldInnerClasses']) ? $classes.' '.$options['fieldInnerClasses'] : $classes;
	}

	protected function getRendererClasses(array $options)
	{	
		$classes = theme_config('forms.renderer-classes.'.$options['type']) ?? theme_config('forms.renderer-default-classes') ?? '';
		return isset($options['rendererAttributes']['class']) ? $classes.' '.$options['rendererAttributes']['class'] : $classes;
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