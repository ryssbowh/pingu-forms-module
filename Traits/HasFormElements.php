<?php

namespace Pingu\Forms\Traits;

use Pingu\Forms\Exceptions\FormFieldException;
use Pingu\Forms\Exceptions\FormelementException;
use Pingu\Forms\Exceptions\GroupException;
use Pingu\Forms\Support\FormElement;
use Pingu\Forms\Support\element;
use Pingu\Forms\Traits\HasGroups;

trait HasFormElements
{
    use HasGroups;
    
    protected $elements;

    /**
     * Adds all elements to the form, but not to any grops yet
     * This is called by the constructor
     * 
     * @param array $elements
     * 
     * @return Form
     */
    protected function makeElements(array $elements)
    {
        $this->elements = collect();
        $this->addElements($elements);
        return $this; 
    }

    /**
     * Add a element to this form, but not to any group
     * 
     * @param element $element
     * @param array   $definition
     * 
     * @throws FormFieldException
     * 
     * @return Form
     */
    public function addElement(FormElement $element, $group = null)
    {
        if ($this->elements->has($element->getName()) === true) {
            throw FormFieldException::alreadyDefined($element->getName(), $this);
        }
        $element->setForm($this);
        $this->elements->put($element->getName(), $element);
        $this->addToGroup($element->getName(), $group);
        return $this;
    }

    /**
     * Add elements to this form and to a group
     * 
     * @param array  $elements
     * @param string $group
     * 
     * @return Form
     */
    public function addElements(array $elements, $group = null)
    {
        foreach ($elements as $element) {
            $this->addelement($element, $group);
        }
        return $this;
    }

    /**
     * Removes elements from this form
     * 
     * @param array $elements
     * 
     * @return Form
     */
    public function removeElements(array $elements)
    {
        foreach ($elements as $name) {
            $this->removeElement($name);
        }
        return $this;
    }

    /**
     * Removes a element from this form
     * 
     * @param string $name
     * 
     * @return Form
     */
    public function removeElement(string $name)
    {
        $this->elements->forget($name);
        $group = $this->searchFieldGroup($name);
        if ($group) {
            $group->forget($group->search($name));
        }
        return $this;
    }

    /**
     * element getter
     * 
     * @param string $name
     * 
     * @throws FormFieldException
     * 
     * @return FormElement
     */
    public function getElement(string $name)
    {
        if (!$this->hasElement($name)) {
            throw FormFieldException::notDefined($name, $this);
        }
        return $this->elements->get($name); 
    }

    /**
     * Gets several or all elements from that form
     * 
     * @param array|null $names
     * 
     * @return array
     */
    public function getElements(?array $names = null)
    {
        if (is_null($names)) {
            $elements = $this->elements->toArray();
        } else {
            $elements = $this->elements->only($names)->toArray();
        }
        return $elements;
    }

    /**
     * Does this form has a element called $name
     * 
     * @param string $name
     * 
     * @return boolean
     */
    public function hasElement(string $name)
    {
        return $this->elements->has($name);
    }

    /**
     * Get all element names for that form
     * 
     * @return array
     */
    public function getElementNames()
    {
        return $this->elements->keys()->all();
    }
}