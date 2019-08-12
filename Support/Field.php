<?php
namespace Pingu\Forms\Support;

use Pingu\Core\Traits\HasViewSuggestions;
use Pingu\Forms\Exceptions\FormFieldException;
use Pingu\Forms\Support\Form;
use Pingu\Forms\Support\Type;
use Pingu\Forms\Support\Types\Text;

abstract class Field
{
	use HasViewSuggestions;

	protected $required = [];
	protected $name;
	public $options;
	public $attributes;
	protected $form;
	protected $value;
	public $type;

	/**
	 * Field constructor, will set default label and type, and turn options and attributes into Collections.
	 * Will check the required options for that field, and set the value.
	 * 
	 * @param string $name
	 * @param array  $options
	 * @param array  $attributes
	 */
	public function __construct(string $name, array $options = [], array $attributes = [])
	{	
		foreach($this->required as $attr){
			if(!isset($options[$attr])){
				throw FormFieldException::missingOption($name, $attr);
			}
		}
		$this->name = $name;
		if(!isset($options['label'])) $options['label'] = label($this->name);
		if(!isset($options['type'])) $options['type'] = $this->getDefaultType();
		$options['type'] = new $options['type']($this);
		$this->options = collect($options);
		$this->attributes = collect($attributes);
		$this->setValue($this->options->get('default') ?? null);
	}

	/**
	 * Get the default type for that field
	 * 
	 * @return string
	 */
	public static function getDefaultType()
	{
		return Text::class;
	}

	/**
	 * Generates the field wrapper classes from config
	 * 
	 * @return type
	 */
	protected function getWrapperClasses()
    {
        return theme_config('forms.wrapper-classes.'.$this->getName()) ??
            theme_config('forms.wrapper-default-classes').' wrapper-field-'.$this->getName();
    }

    /**
	 * Generates the label classes from config
	 * 
	 * @return type
	 */
    protected function getLabelClasses()
    {
        return theme_config('forms.label-classes.'.$this->getName()) ??
            theme_config('forms.label-default-classes').' label-field-'.$this->getName();
    }

    /**
	 * Generates the field classes from config
	 * 
	 * @return type
	 */
    protected function getClasses(?string $default = '')
    {
    	return trim($default.' '.
    		(theme_config('forms.field-classes.forms.'.$this->form->getName().'.'.$this->getType()) ??
    		theme_config('forms.field-classes.default.'.$this->getType()) ??
    		''
    		));
    }

    /**
     * Sets the form for that field
     * 
     * @param Form $form
     */
    public function setForm(Form $form)
    {
    	$this->form = $form;
    	$this->setViewSuggestions([
			'forms.fields.form-'.$this->form->getName().'_'.$this->getType(),
			'forms.fields.'.$this->getType(),
			$this->getDefaultView()
		]);
		$this->wrapperClasses = $this->getWrapperClasses();
		$this->labelClasses = $this->getLabelClasses();
		$this->attributes->put('class', $this->getClasses($this->attributes->get('class', '')));
    }

    /**
     * Sets/gets an option
     * 
     * @param  string $name
     * @param  mixed $value
     * @return Form|mixed
     */
	public function option(string $name, $value = null)
	{
		if(!is_null($value)){
			$this->options->put($name, $value);
			return $this;
		}
		return $this->options->get($name);
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
	 * Get attributes as array
	 * 
	 * @return array
	 */
	public function getAttributes()
	{
		return $this->attributes->toArray();
	}

	/**
	 * type getter, this is used to find the right view.
	 * 
	 * @return string
	 */
	public function getType()
	{
		return strtolower(class_basename($this));
	}

	/**
	 * name getter 
	 * 
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Value getter
	 * 
	 * @return mixed
	 */
	public function getValue()
	{
		return $this->value;
	}

	/**
	 * Value setter
	 * 
	 * @param mixed
	 * @return  Form
	 */
	public function setValue($value)
	{
		$this->value = $value;
		return $this;
	}

	public function addValidationRules()
	{
		return '';
	}

	/**
	 * Renders this field
	 */
	public function render()
	{
		echo view()->first($this->getViewSuggestions(),['field' => $this])->render();
	}

	/**
	 * Helper to build a field from a array of definition
	 * @param  string $name
	 * @param  array  $definition
	 * @return Field
	 */
	public static function buildFieldClass(string $name, array $definition)
    {
        return new $definition['field']($name, $definition['options'] ?? [], $definition['attributes'] ?? []);
    }

   	public abstract function getDefaultView();
}