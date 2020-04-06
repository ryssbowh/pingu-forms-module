<?php 

namespace Pingu\Forms\Support;

use Pingu\Core\Contracts\RenderableContract;
use Pingu\Core\Traits\RendersWithRenderer;

abstract class FormElement implements RenderableContract
{
    use RendersWithRenderer;
    
    /**
     * Name for this element
     * 
     * @return string
     */
    abstract public function getName(): string;

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
}