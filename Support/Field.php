<?php
namespace Pingu\Forms\Support;

use Pingu\Core\Traits\RendersWithSuggestions;
use Pingu\Forms\Exceptions\FormFieldException;
use Pingu\Forms\Support\AttributeBag;
use Pingu\Forms\Support\Form;
use Pingu\Forms\Support\Type;
use Pingu\Forms\Support\Types\Text;
use Pingu\Forms\Traits\HasAttributes;
use Pingu\Forms\Traits\HasOptions;

abstract class Field extends FormElement
{
    use RendersWithSuggestions, HasOptions, HasAttributes;

    protected $name;
    protected $form;
    protected $value;
    protected $requiredOptions = [];
    public $classes;
    public $wrapperClasses;
    public $labelClasses;
    protected $index = 0;
    
    public function __construct(string $name, array $options = [], array $attributes = [])
    {   
        foreach ($this->requiredOptions as $option) {
            if (!isset($options[$option])) {
                throw FormFieldException::missingOption($name, $option);
            }
        }
        $this->name = $name;
        if (!isset($options['label'])) {
            $options['label'] = label($this->name);
        }
        $attributes['required'] = $options['required'] ?? false;
        $this->setValue($options['default'] ?? null);

        $this->buildOptions($options);
        $this->buildAttributes($attributes);
        
        $this->classes = new ClassBag([
            'field'
        ]);
        $this->wrapperClasses = new ClassBag([
            'field-wrapper',
            'form-element',
            'field-wrapper-'.$name,
            'field-wrapper-type-'.$this->getType(),
        ]);
        $this->labelClasses = new ClassBag([
            'field-label',
            'field-label-'.$name,
        ]);
        $this->setViewSuggestions([
            'forms.field-'.$this->name,
            'forms.field-'.$this->getType(),
            'forms::fields.'.$this->getType(),
            'forms.field',
            'forms::field'
        ]);
    }

    protected function isMultiple(): bool
    {
        return $this->option('multiple') ?? false;
    }

    public function setIndex(int $index)
    {
        $this->index = $index;
    }

    /**
     * Sets the form for that field
     * 
     * @param Form $form
     */
    public function setForm(Form $form)
    {
        $this->form = $form;
        $this->addViewSuggestions([
            'forms.fields.form-'.$form->getName().'_'.$this->getType(),
            'forms.fields.form-'.$form->getName().'_'.$this->getName(),
        ]);
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
        return $this->name . ($this->isMultiple() ? '['.$this->index.']' : '');
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

    public function getViewData()
    {
        $this->attributes->put('class', $this->classes->get(true));
        return [
            'field' => $this,
            'wrapperClasses' => $this->wrapperClasses->get(true),
            'attributes' => $this->attributes,
            'labelClasses' => $this->labelClasses->get(true),
        ];
    }
}