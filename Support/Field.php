<?php

namespace Pingu\Forms\Support;

use Pingu\Forms\Contracts\FieldContract;
use Pingu\Forms\Contracts\FormContract;

abstract class Field implements FieldContract
{
	protected $form;
	public $name;
	public $label;
	public $value;
	public $renderer;
	public $classes;
	public $labelClasses;
	public $attributes;

	public function __construct(string $name, array $attributes = [], ?FormContract $form = null)
	{
		$this->name = $name;
		$this->form = $form;

		$this->attributes = new AttributeBag($attributes['attributes'] ?? []);

		unset($attributes['attributes']);
		foreach($attributes as $attribute => $value){
			$this->$attribute = $value;
		}
		$renderer = $this->renderer ?? $this->getDefaultRenderer();
		$this->renderer = new $renderer($this);
		$this->label = $this->label ?? ucfirst($this->name);
		$this->attributes->add('class', $this->getRendererClasses());
		$this->classes = new ClassBag($this->getClasses());
		$this->labelClasses = new ClassBag($this->getLabelClasses());
		$this->innerClasses = new ClassBag($this->getInnerClasses());
	}

	protected function getClasses()
    {
        return theme_config('forms.field-classes.'.$this->getName()) ??
            theme_config('forms.field-default-classes').' form-field-'.$this->getName();
    }

    protected function getLabelClasses()
    {
        return theme_config('forms.label-classes.'.$this->getName()) ??
            theme_config('forms.label-default-classes').' label-'.$this->getName();
    }

    protected function getInnerClasses()
    {
    	return theme_config('forms.field-inner-classes.'.$this->getName()) ??
            theme_config('forms.field-inner-default-classes').' field-'.$this->getName().'-inner';
    }

    protected function getRendererClasses()
    {
    	return theme_config('forms.renderer-classes.'.$this->getType());
    }

	/**
	 * @inheritDoc
	 */
	public function setValue($value)
	{
		$this->value = $value;
		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function getValue()
	{
		return $this->value ?? null;
	}

	/**
	 * @inheritDoc
	 */
	public function getType()
	{
		return $this->renderer->getType();
	}

	/**
	 * @inheritDoc
	 */
	public function getName()
	{
		return $this->name;
	}

	protected function getViewSuggestions()
	{
		return ['forms.fields.field-'.$this->getType(), 'forms.fields.field', 'forms::fields.'.$this->getType()];
	}

	/**
	 * @inheritDoc
	 */
	public function render()
	{
		echo view()->first($this->getViewSuggestions(), ['field' => $this]);
	}
}