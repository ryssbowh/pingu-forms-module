<?php

namespace Pingu\Forms\Support;

use Illuminate\Support\Arr;

class ClassBag
{
    protected $classes = [];

    public function __construct($classes)
    {
        $this->add($classes);
    }
    /**
     * Add a class
     * 
     * @param string|array $class
     * 
     * @return Form
     */
    public function add($classes)
    {
        if (is_string($classes)) {
            $classes = preg_split('/\s+/', trim($classes));
        }
        $this->classes = array_unique(
            array_merge(
                $classes,
                $this->classes
            )
        );
        return $this;
    }

    /**
     * remove a class
     * 
     * @param string|array $class
     * 
     * @return Form
     */
    public function remove($classes)
    {
        if (is_string($classes)) {
            $classes = preg_split('/\s+/', trim($classes));
        }
        $this->classes = array_diff($this->classes, $classes);
        return $this;
    }

    public function get(bool $toHtml = false)
    {
        if ($toHtml) {
            return implode(' ', $this->classes);
        }
        return $this->classes;
    }

    public function set($classes)
    {
        if (is_string($classes)) {
            $classes = preg_split('/\s+/', trim($classes));
        }
        $this->classes = $classes;
        return $this;
    }
}