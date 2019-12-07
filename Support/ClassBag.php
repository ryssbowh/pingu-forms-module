<?php

namespace Pingu\Forms\Support;

use Illuminate\Support\Arr;

class ClassBag
{
    protected $classes = [];

    /**
     * Constructor
     * 
     * @param array|string $classes
     */
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

    /**
     * Get classes
     * 
     * @param bool|boolean $toHtml
     * 
     * @return string|array
     */
    public function get(bool $toHtml = false)
    {
        if ($toHtml) {
            return implode(' ', $this->classes);
        }
        return $this->classes;
    }

    /**
     * Set classes
     * 
     * @param string|array $classes
     */
    public function set($classes)
    {
        if (is_string($classes)) {
            $classes = preg_split('/\s+/', trim($classes));
        }
        $this->classes = $classes;
        return $this;
    }
}