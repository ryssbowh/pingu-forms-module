<?php

namespace Pingu\Forms\Traits;

use Pingu\Forms\Exceptions\FormFieldException;
use Pingu\Forms\Support\FormElement;

trait HasFormElements
{   
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
     * Add a element to this form
     * 
     * @param element $element
     * @param array   $definition
     * 
     * @throws FormFieldException
     * 
     * @return Form
     */
    public function addElement(FormElement $element)
    {
        if ($this->elements->has($element->getName()) === true) {
            throw FormFieldException::alreadyDefined($element->getName(), $this);
        }
        $element->setForm($this);
        $this->elements->put($element->getName(), $element);
        return $this;
    }

    /**
     * Add elements to this form
     * 
     * @param array  $elements
     * 
     * @return Form
     */
    public function addElements(array $elements)
    {
        foreach ($elements as $element) {
            $this->addelement($element);
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
        if ($this->elements->has($name)) {
            $this->elements->forget($name);
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