<?php

namespace Pingu\Forms\Support;

use Pingu\Core\Contracts\RendererContract;
use Pingu\Core\Traits\RendersWithRenderer;
use Pingu\Forms\Support\ClassBag;
use Pingu\Forms\Support\Renderers\FormGroupRenderer;
use Pingu\Forms\Traits\HasOptions;

class FormGroup extends FormElement
{
    use HasOptions, RendersWithRenderer;

    protected $fields;
    protected $name;
    protected $form;
    public $classes;
    public $labelClasses;

    public function __construct(string $name, array $fields, Form $form, array $options = [])
    {
        $this->name = strtolower($name);
        $this->fields = $fields;
        $this->buildOptions($options);
        $this->setForm($form);
        $this->classes = new ClassBag(
            [
            'form-element',
            'form-group',
            'form-group-'.$this->name
            ]
        );
        $this->labelClasses = new ClassBag(
            [
            'form-element-label',
            'form-group-label',
            'form-group-label-'.$this->name
            ]
        );
    }

    /**
     * Form getter
     * 
     * @return Form
     */
    public function getForm(): Form
    {
        return $this->form;
    }

    /**
     * Has a field
     * 
     * @param string  $name
     * 
     * @return boolean
     */
    public function hasField(string $name)
    {
        return in_array($name, $this->fields);
    }

    /**
     * Remove a field
     * 
     * @param string $name
     * 
     * @return FormGroup
     */
    public function removeField(string $name): FormGroup
    {
        if ($this->hasField($name)) {
            $index = array_search($name, $this->fields);
            unset($this->fields[$index]);
        }
        return $this;
    }
    
    /**
     * Add a field
     * 
     * @param string   $name
     * @param int|null $index
     *
     * @return FormGroup
     */
    public function addField(string $name, ?int $index = null): FormGroup
    {
        if ($this->hasField($name)) {
            return $this;
        }
        if (is_null($index)) {
            $this->fields[] = $name;
        } else {
            array_splice($this->fields, $index, 0, $name);
        }
        return $this;
    }

    /**
     * Add fields
     * 
     * @param array $fields
     *
     * @return FormGroup
     */
    public function addFields(array $fields): FormGroup
    {
        foreach ($fields as $field) {
            $this->addField($field);
        }
        return $this;
    }

    /**
     * Moves a field up.
     * Offset can be false, in which case the field will be moved at the top.
     * If the offset is negative, field will be moved down.
     * 
     * @param string  $name
     * @param boolean $offset
     * 
     * @return FormGroup
     */
    public function moveFieldUp(string $name, $offset = false): FormGroup
    {
        if (!$this->hasField($name)) {
            return $this;
        }
        if ($offset === false) {
            return $this->moveElementToTop($name);
        }
        if ($offset < 0) {
            return $this->moveElementDown($name, $offset*-1);
        }
        $index = array_search($name, $this->fields);
        $newIndex = $index - $offset;
        if ($newIndex < 0) {
            $newIndex = 0;
        }
        $this->removeField($name);
        $this->addField($name, $newIndex);
        return $this;
    }

    /**
     * Moves a field down in its group.
     * Offset can be false, in which case the field will be moved at the bottom.
     * If the offset is negative, field will be moved up.
     * 
     * @param string  $name
     * @param boolean $offset
     * 
     * @return FormGroup
     */
    public function moveFieldDown(string $name, $offset = false): FormGroup
    {
        if (!$this->hasField($name)) {
            return $this;
        }
        if ($offset === false) {
            return $this->moveElementToBottom($name);
        }
        if ($offset < 0) {
            return $this->moveElementUp($name, $offset*-1);
        }
        $index = array_search($name, $this->fields);
        $size = count($this->fields);
        $newIndex = $index + $offset;
        if ($newIndex > ($size-1)) {
            $newIndex = $size - 1;
        }
        $this->removeField($name);
        $this->addField($name, $newIndex);
        return $this;
    }

    /**
     * Moves a field a the top of its group
     * 
     * @param string $name
     * 
     * @return FormGroup
     */
    public function moveFieldToTop(string $name): FormGroup
    {
        $this->removeField($name);
        $this->addField($name, 0);
        return $this;
    }

    /**
     * Moves a field at the bottom of its group
     * 
     * @param string $name
     * 
     * @return FormGroup
     */
    public function moveFieldToBottom(string $name): FormGroup
    {
        $this->removeField($name);
        $this->addField($name);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getRenderer(): RendererContract
    {
        return new FormGroupRenderer($this);
    }

    /**
     * Set the form for this group
     * 
     * @param Form $form
     *
     * @return FormGroup
     */
    public function setForm(Form $form): FormGroup
    {
        $this->form = $form;
        return $this;
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

    /**
     * Name getter
     * 
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function getDefaultViewName(): string
    {
        return 'forms@form-group';
    }
}