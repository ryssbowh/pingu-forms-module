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

    public function __construct(string $name, array $options, array $fields)
    {
        $this->name = $name;
        $this->fields = $fields;
        $this->buildOptions($options);
        $this->setViewSuggestions([
            'forms.field-group-'.$this->name,
            'forms.field-group',
            'forms::field-group'
        ]);
        $this->classes = new ClassBag([
            'form-element',
            'field-group',
            'field-group-'.$name
        ]);
        $this->labelClasses = new ClassBag([
            'form-element-label',
            'form-group-label',
            'form-group-label-'.$name
        ]);
    }

    public function setForm(Form $form)
    {
        $this->form = $form;
        foreach ($this->fields as $field) {
            $field->setForm($form);
        }
    }

    public function attribute(string $name, $value)
    {
        foreach ($this->fields as $field) {
            $field->attribute($name, $value);
        }
        return $this;
    }

    public function first(): ?FormElement
    {
        return $this->fields[0] ?? null;
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function getName(): string
    {
        return $this->name;
    }

    protected function getViewData(): array
    {
        return [
            'classes' => $this->classes->get(true),
            'labelClasses' => $this->labelClasses->get(true),
            'fields' => $this->fields,
            'group' => $this
        ];
    }
}