<?php
namespace Pingu\Forms\Support;

use Pingu\Core\Traits\RendersWithSuggestions;
use Pingu\Forms\Exceptions\FormFieldException;
use Pingu\Forms\Support\AttributeBag;
use Pingu\Forms\Support\Form;
use Pingu\Forms\Support\Type;
use Pingu\Forms\Support\Types\Text;
use Pingu\Forms\Traits\HasAttributesFromOptions;
use Pingu\Forms\Traits\HasOptions;

abstract class Field extends FormElement
{
    use RendersWithSuggestions, HasOptions, HasAttributesFromOptions;

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
     * Index of this field in its group
     * 
     * @var int
     */
    protected $index = 0;

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
        $options['label'] = $options['label'] ?? form_label($this->name);
        $options['showLabel'] = $options['showLabel'] ?? true;

        $this->setValue($options['default'] ?? null);

        $this->buildOptions($options);
        
        $this->classes = new ClassBag($this->getDefaultClasses());
        $this->wrapperClasses = new ClassBag($this->getDefaultWrapperClasses());
        $this->labelClasses = new ClassBag($this->getDefaultLabelClasses());
        $this->setViewSuggestions(
            [
            'forms.fields.'.$this->getType().'-'.$this->name,
            'forms.fields.'.$this->getType(),
            $this->getDefaultViewSuggestion()
            ]
        );
    }

    public static function options(): string
    {
        return FieldOptions::class;
    }

    public static function machineName(): string
    {
        return class_machine_name(static::class);
    }

    /**
     * Friendly name for this field
     * 
     * @return string
     */
    public static function friendlyname()
    {
        return friendly_classname(static::class);
    }

    protected function getDefaultClasses()
    {
        return [
            'field',
            'field-'.$this->name,
            'field-'.$this->getType()
        ];
    }

    protected function getDefaultWrapperClasses()
    {
        return [
            'field-wrapper',
            'form-element',
            'field-wrapper-'.$this->name,
            'field-wrapper-type-'.$this->getType(),
        ];
    }

    protected function getDefaultLabelClasses()
    {
        return [
            'field-label',
            'field-label-'.$this->name,
            'field-label-'.$this->getType(),
        ];
    }

    /**
     * @inheritDoc
     */
    protected function getDefaultViewSuggestion(): string
    {
        return 'forms@fields.'.$this->getType();
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
     * get index
     * 
     * @return int
     */
    public function getIndex(): int
    {
        return $this->index;
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
        return $this->htmlName ?? $this->name . ($this->isMultiple() ? '['.$this->index.']' : '');
    }

    /**
     * Html Name setter
     * @param string $name
     */
    public function setHtmlName(string $name)
    {
        $this->htmlName = $name;
        return $this;
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
        $attributes = $this->buildAttributes();
        $attributes['class'] = $this->classes->get(true);
        return [
            'field' => $this,
            'wrapperClasses' => $this->wrapperClasses->get(true),
            'attributes' => $attributes,
            'labelClasses' => $this->labelClasses->get(true),
        ];
    }
}