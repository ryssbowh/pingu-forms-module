<?php 

namespace Pingu\Forms\Traits;

trait HasOptions
{
    protected $options;

    /**
     * Build options
     * 
     * @param array $options
     */
    protected function buildOptions(array $options = [])
    {
        $options = array_merge($this::defaultOptions(), $options);
        $this->options = collect($options);
    }

    /**
     * Default options
     * 
     * @return array
     */
    public static function defaultOptions(): array
    {
        return [];
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