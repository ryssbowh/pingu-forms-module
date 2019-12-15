<?php 

namespace Pingu\Forms\Traits;

use Pingu\Forms\Support\AttributeBag;

trait HasAttributesFromOptions
{
    /**
     * Build the field attributes
     * 
     * @return AttributeBag
     */
    protected function buildAttributes(): AttributeBag
    {
        return new AttributeBag($this->options->only($this->attributeOptions ?? [])->all());
    }
}