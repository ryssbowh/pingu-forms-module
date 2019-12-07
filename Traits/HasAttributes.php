<?php 

namespace Pingu\Forms\Traits;

use Pingu\Forms\Support\AttributeBag;

trait HasAttributes
{
    /**
     * @var AttributeBag
     */
    public $attributes;

    /**
     * Build the field attributes
     * 
     * @param array $attributes
     */
    protected function buildAttributes(array $attributes = [])
    {
        $this->attributes = new AttributeBag($attributes);
    }

    /**
     * Build the field attributes from an array of option keys
     * 
     * @param array $keys
     * 
     * @return AttributeBag
     */
    protected function buildAttributesFromOptions(array $keys = [])
    {
        $attributes = [];
        foreach ($keys as $key) {
            $attributes[$key] = $this->option($key);
        }
        $this->attributes = new AttributeBag($attributes);
    }

    /**
     * Sets/gets an attribute
     * 
     * @param string $name
     * @param mixed  $value
     * 
     * @return Form|mixed
     */
    public function attribute(string $name, $value = null)
    {
        if (!is_null($value)) {
            $this->attributes->put($name, $value);
            return $this;
        }
        return $this->attributes->get($name);
    }
}