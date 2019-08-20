<?php
namespace Pingu\Forms\Traits;

trait HasClasses
{
    /**
     * Add a class
     * 
     * @param string $class
     * @return Form
     */
    public function addClass(string $class)
    {
        $classes = array_unique(array_merge(
            [$class],
            explode(',', $this->attribute('class') ?? '')
        ));
        $this->attribute('class', implode(' ', $classes));
        return $this;
    }

    /**
     * Add classes
     * 
     * @param string $classes
     * @return Form
     */
    public function addClasses(string $classes)
    {
        $classes = array_unique(array_merge(
            explode(' ', $classes),
            explode(' ', $this->attribute('class') ?? '')
        ));
        $this->attribute('class', implode(' ', $classes));
        return $this;
    }

    /**
     * remove a class
     * 
     * @param  string $class
     * @return Form
     */
    public function removeClass(string $class)
    {
        $classes = explode(' ', $this->attribute('class'));
        $ind = array_search($class, $classes);
        if($ind !== false){
            unset($classes[$ind]);
        }
        $this->attribute('class', implode(' ', $classes));
        return $this;
    }
}