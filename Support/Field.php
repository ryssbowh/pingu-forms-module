<?php

namespace Pingu\Forms\Support;

use Pingu\Core\Contracts\RendererContract;
use Pingu\Core\Traits\RendersWithRenderer;
use Pingu\Forms\Exceptions\FormFieldException;
use Pingu\Forms\Support\AttributeBag;
use Pingu\Forms\Support\Form;
use Pingu\Forms\Support\Renderers\FormFieldRenderer;
use Pingu\Forms\Support\Type;
use Pingu\Forms\Support\Types\Text;
use Pingu\Forms\Traits\HasAttributesFromOptions;
use Pingu\Forms\Traits\HasOptions;

abstract class Field extends FormElement
{
    use HasOptions, HasAttributesFromOptions, RendersWithRenderer;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $htmlName;

    /**
     * @var Form
     */
    protected $form;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @var array
     */
    protected $requiredOptions = [];

    /**
     * @var ClassBag
     */
    public $classes;

    /**
     * @var ClassBag
     */
    public $wrapperClasses;

    /**
     * @var ClassBag
     */
    public $labelClasses;

    /**
     * @inheritDoc
     */
    protected $attributeOptions = ['required', 'id'];
    
    /**
     * Constructor
     * 
     * @param string $name
     * @param array  $options
     */
    public function __construct(string $name, array $options = [])
    {   
        foreach ($this->requiredOptions as $option) {
            if (!isset($options[$option])) {
                throw FormFieldException::missingOption($name, $option);
            }
        }
        $this->name = $name;
        $this->init($options);
    }

    protected function init(array $options)
    {
        $this->setValue($options['default'] ?? null);

        $this->buildOptions(array_merge($this->getDefaultOptions(), $options));
        
        $this->classes = new ClassBag($this->getDefaultClasses());
        $this->wrapperClasses = new ClassBag($this->getDefaultWrapperClasses());
        $this->labelClasses = new ClassBag($this->getDefaultLabelClasses());
    }

    /**
     * Class to render this field
     * 
     * @return RendererContract
     */
    public function getRenderer(): RendererContract
    {
        return new FormFieldRenderer($this);
    }

    /**
     * Get the field option class
     * 
     * @return string
     */
    public static function options(): string
    {
        return FieldOptions::class;
    }

    /**
     * Machine name getter
     * 
     * @return string
     */
    public static function machineName(): string
    {
        return class_machine_name(static::class);
    }

    /**
     * Default classes for this field
     * 
     * @return array
     */
    protected function getDefaultClasses(): array
    {
        return [
            'field',
            'field-'.$this->name,
            'field-'.$this->getType()
        ];
    }

    /**
     * Default classes for this field's wrapper
     * 
     * @return array
     */
    protected function getDefaultWrapperClasses(): array
    {
        return [
            'field-wrapper',
            'form-element',
            'field-wrapper-'.$this->name,
            'field-wrapper-type-'.$this->getType(),
        ];
    }

    /**
     * Default classes for this field's label
     * 
     * @return array
     */
    protected function getDefaultLabelClasses(): array
    {
        return [
            'field-label',
            'field-label-'.$this->name,
            'field-label-'.$this->getType(),
        ];
    }

    /**
     * Field default options
     * 
     * @return array
     */
    protected function getDefaultOptions(): array
    {
        return [
            'showLabel' => true,
            'label' => form_label($this->name)
        ];
    }

    /**
     * @inheritDoc
     */
    public function setForm(Form $form)
    {
        $this->form = $form;
    }

    /**
     * @inheritDoc
     */
    public function getForm(): Form
    {
        return $this->form;
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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * name getter to be displayed in an html form
     * 
     * @return string
     */
    public function getHtmlName()
    {
        return $this->option('htmlName') ?? $this->name . ($this->option('multiple') ? '['.$this->option('index').']' : '');
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
     * 
     * @return Form
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getDefaultViewName(): string
    {
        return 'forms@fields.'.$this->getType();
    }
}