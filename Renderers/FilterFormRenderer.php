<?php

namespace Pingu\Forms\Renderers;

use Pingu\Forms\Support\ClassBag;

class FilterFormRenderer extends FormRenderer
{
    protected function getDefaultClasses(): ClassBag
    {
        return new ClassBag([
            'form-filter', 
            'form-filter-entity', 
            'form-'.$this->object->getName()
        ]);
    }
}