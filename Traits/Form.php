<?php
namespace Pingu\Forms\Traits;

use Pingu\Forms\Events\FormBuilt;
use Pingu\Forms\Exceptions\FormException;
use Pingu\Forms\Support\ClassBag;
use Pingu\Forms\Traits\HasFields;
use Pingu\Forms\Traits\RendersForm;

trait Form
{
    use RendersForm, HasFields;

    public $attributes;
    public $options;
    protected $name;
    protected $built = false;

    public function __construct()
    {
        $this->name = $this->makeName($this->name());
        $this->id = $this->id();
        $this->options = collect($this->options());
        $this->attributes = collect([
            'method' => $this->method(),
            'files' => true,
            'id' => $this->id(),
            'class' => $this->getClasses()
        ]);
        $this->makeUrl($this->url());
        $this->makeFields($this->fields());
        $this->makeGroups($this->groups());
        $this->buildViewSuggestions();
    }

    protected function makeName(string $name)
    {
        if (!preg_match('/^[A-Za-z_\-]+$/', $name)){
            throw FormException::name($name);
        }
        return $name;
    }

    public function getName()
    {
        return $this->name;
    }

    protected function makeUrl(array $url)
    {
        $key = array_keys($url)[0];
        $this->attributes->put($key, $url[$key]);
    }

    protected function getClasses()
    {
        $classes = theme_config('forms.classes.'.$this->name) ?? theme_config('forms.default-classes');
        $classes .= ' form-'.$this->name;
        return $classes;
    }

    /**
     * Marks this form as built, populates the fields if applicable and sends an event
     *
     * @return Form
     */
    public function built()
    {   
        $this->built = true;
        \FormFacade::considerRequest();
        event(new FormBuilt($this->getName(), $this));
        return $this;
    }

}