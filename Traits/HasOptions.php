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
     * Merge options
     * 
     * @param array  $options
     * 
     * @return mixed
     */
    public function mergeOptions(array $options)
    {
        $this->options = $this->options->merge(collect($options));
        return $this;
    }

    /**
     * Sets/gets an option
     * 
     * @param string $name
     * @param mixed  $value
     * 
     * @return mixed
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