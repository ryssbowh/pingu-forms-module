<?php 

namespace Pingu\Forms\Traits;

trait HasOptions
{
    public $options;

    /**
     * Build options
     * 
     * @param array $options
     */
    protected function buildOptions(array $options = [])
    {
        $this->options = collect($options);
    }

    /**
     * Sets/gets an option
     * 
     * @param string $name
     * @param mixed  $value
     * 
     * @return Form|mixed
     */
    public function option(string $name, $value = null)
    {
        if (!is_null($value)) {
            $this->options->put($name, $value);
            return $this;
        }
        return $this->options->get($name);
    }
}