<?php 

namespace Pingu\Forms\Support;

use Pingu\Core\Traits\RendersWithRenderer;

abstract class FormElement
{
    use RendersWithRenderer;
    
    /**
     * Name for this element
     * 
     * @return string
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

    /**
     * Form getter
     * 
     * @param Form $form
     */
    abstract public function getForm(): Form;

    /**
     * View name to render this element
     * 
     * @return string
     */
    abstract public function getDefaultViewName(): string;
}