<?php 

namespace Pingu\Forms\Support;

abstract class FormElement
{
    /**
     * Name for this element
     * 
     * @return [string
     */
    abstract public function getName(): string;

    /**
     * Renders this element
     */
    abstract public function render();

    /**
     * Set this element's form
     * 
     * @param Form $form
     */
    abstract public function setForm(Form $form);
}