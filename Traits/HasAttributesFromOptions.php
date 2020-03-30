<?php 

namespace Pingu\Forms\Traits;

use Pingu\Forms\Support\AttributeBag;

trait HasAttributesFromOptions
{
    protected $attributes = [];
    /**
     * Build the field attributes
     * 
     * @return AttributeBag
     */
    public function buildAttributes(): AttributeBag
    {
        $attributes = array_merge(
            $this->options->only($this->getAttributesOptions())->all(),
            $this->attributes
        );
        return new AttributeBag($attributes);
    }

    protected function getAttributesOptions()
    {
        return $this->attributeOptions ?? [];
    }

    public function attribute($name, $value)
    {
        $this->attributes[$name] = $value;
    }
}