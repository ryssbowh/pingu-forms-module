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
     * @param array  $definition
     * 
     * @throws FormFieldException
     * 
     * @return element
     */
    public function addElement(FormElement $element)
    {
        if ($this->elements->has($element->getName()) === true) {
            throw FormFieldException::alreadyDefined($name, $this);
        }
        $element->setForm($this);
        $this->elements->put($element->getName(), $element);
        return $element;
    }

    /**
     * Add several elements to this forms, but not to any groups
     * 
     * @param array $elements
     * 
     * @return Form
     */
    // protected function _addelements(array $elements)
    // {
    //     foreach ($elements as $element) {
    //         $this->_addelement($element);
    //     }
    //     return $this;
    // }

    /**
     * Add elements to this form and to a group
     * 
     * @param array  $elements
     * @param string $group
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
     * Adds a element to this form and to a group
     * 
     * @param element $element
     * @param string $group
     * 
     * @return Form
     */
    // public function addelement(element $element, $group = 'default')
    // {
    //     $group = $this->getGroup($group);
    //     $element = $this->_addelement($element);
    //     $group->push($element->getName());
    //     return $this;
    // }

    /**
     * Removes elements from this form
     * 
     * @param array  $elements
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
        // $group = $this->searchelementGroup($name);
        // if ($group) {
        //     $group->forget($group->search($name));
        // }
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
            throw FormFieldException::notDefined($name);
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
     * @param string  $name
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

    /**
     * Sets the value for a element in that form
     *
     * @param string $name
     * @param mixed $value
     * 
     * @return Form
     */
    // public function setElementValue(string $name, $value)
    // {
    //     $this->getelement($name)->setValue($value);
    //     return $this;
    // }

    /**
     * Sets elements values
     *
     * @param array $values
     * 
     * @return Form
     */
    // public function setelementValues(array $values)
    // {
    //     foreach ($values as $name => $value) {
    //         $this->setelementValue($name, $value);
    //     }
    //     return $this;
    // }

    /**
     * Gets the value for a element in that form
     * 
     * @param string $name
     * @param mixed $value
     * 
     * @return mixed
     */
    // public function getelementValue(string $name, $value)
    // {
    //     return $this->getelement($name)->getValue($value);
    // }
}