<?php

namespace Pingu\Forms\Support;

use Pingu\Core\Traits\RendersWithSuggestions;
use Pingu\Forms\Support\ClassBag;
use Pingu\Forms\Support\Form;
use Pingu\Forms\Support\FormElement;
use Pingu\Forms\Traits\HasOptions;

class FieldGroup extends FormElement
{
    use RendersWithSuggestions, HasOptions;

    protected $fields;
    protected $name;
    protected $form;
    public $classes;
    public $labelClasses;
    protected $cardinality;

    public function __construct(string $name, array $options, array $fields, int $cardinality = 1)
    {
        $this->name = $name;
        $this->fields = $fields;
        $this->cardinality = $cardinality;
        $this->buildOptions($options);
        $this->setViewSuggestions(
            [
            'forms.field-group-'.$this->name,
            'forms.field-group',
            $this->getDefaultViewSuggestion()
            ]
        );
        $this->classes = new ClassBag(
            [
            'form-element',
            'field-group',
            'field-group-'.class_machine_name($this->fields[0]),
            'field-group-'.$name
            ]
        );
        $this->labelClasses = new ClassBag(
            [
            'form-element-label',
            'form-group-label',
            'form-group-label-'.$name
            ]
        );
    }

    /**
     * Cardinality getter
     * 
     * @return int
     */
    public function getCardinality(): int
    {
        return $this->cardinality;
    }

    /**
     * Set the form for this group
     * 
     * @param Form $form
     */
    public function setForm(Form $form)
    {
        $this->form = $form;
        $this->addViewSuggestions(
            [
            'forms.field-group-form-'.$form->getName().'_'.$this->name,
            'forms.field-group-form-'.$form->getName(),
            ]
        );
        foreach ($this->fields as $field) {
            $field->setForm($form);
        }
    }

    /**
     * Set an option for all the fields in this group
     * 
     * @param string $name
     * @param FieldGroup $value
     */
    public function setOptions(string $name, $value)
    {
        foreach ($this->fields as $field) {
            $field->option($name, $value);
        }
        return $this;
    }

    /**
     * Merges options for all the fields in this group
     * 
     * @param array      $options
     * @param FieldGroup $value
     */
    public function mergeOptions(array $options)
    {
        foreach ($this->fields as $field) {
            $field->mergeOptions($options);
        }
        return $this;
    }

    /**
     * get the first field in this group
     * 
     * @return ?FormElement
     */
    public function first(): ?FormElement
    {
        return $this->fields[0] ?? null;
    }

    /**
     * Fields getter 
     * 
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    protected function getDefaultViewSuggestion(): string
    {
        return 'forms@field-group';
    }

    /**
     * @inheritDoc
     */
    protected function getViewData(): array
    {
        return [
            'classes' => $this->classes->get(true),
            'labelClasses' => $this->labelClasses->get(true),
            'fields' => $this->fields,
            'group' => $this
        ];
    }

    public function setValue(array $values)
    {
        foreach ($this->fields as $id => $field) {
            $field->setValue($values[$id] ?? null);
        }
        return $this;
    }
}