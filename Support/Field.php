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

    /**
     * @var string
     */
    protected $name;

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
     * Options that are html attributes
     * 
     * @var array
     */
    protected $attributeOptions = ['required'];

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
     * Index of this field in its group
     * 
     * @var int
     */
    protected $index = null;
    
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
        if (!isset($options['label'])) {
            $options['label'] = form_label($this->name);
        }
        $this->setValue($options['default'] ?? null);

        $this->buildOptions($options);
        $this->buildAttributesFromOptions($this->attributeOptions);
        
        $this->classes = new ClassBag(
            [
            'field'
            ]
        );
        $this->wrapperClasses = new ClassBag(
            [
            'field-wrapper',
            'form-element',
            'field-wrapper-'.$name,
            'field-wrapper-type-'.$this->getType(),
            ]
        );
        $this->labelClasses = new ClassBag(
            [
            'field-label',
            'field-label-'.$name,
            ]
        );
        $this->setViewSuggestions(
            [
            'forms.field-'.$this->name,
            'forms.field-'.$this->getType(),
            'forms::fields.'.$this->getType(),
            'forms.field',
            'forms::field'
            ]
        );
    }

    /**
     * Is this field mutiple
     * 
     * @return boolean
     */
    protected function isMultiple(): bool
    {
        return $this->option('multiple') ?? false;
    }

    /**
     * Set the index
     * 
     * @param int $index
     */
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
        $this->addViewSuggestions(
            [
            'forms.fields.form-'.$form->getName().'_'.$this->getType(),
            'forms.fields.form-'.$form->getName().'_'.$this->getName(),
            ]
        );
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

    /**
     * Get view data
     * 
     * @return array
     */
    public function getViewData(): array
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