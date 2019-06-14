<?php
namespace Pingu\Forms\Traits;

use Pingu\Forms\Events\FormBuilt;
use Pingu\Forms\Exceptions\FormException;
use Pingu\Forms\Support\ClassBag;
use Pingu\Forms\Support\Fields\Submit;
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

    /**
     * Removes non-wanted characters from name
     * 
     * @param  string $name
     * @return string
     */
    protected function makeName(string $name)
    {
        return preg_replace('/[^A-Za-z0-9\-]/i', '', $name);
    }

    /**
     * Name getter
     * 
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * moves the action into the attributes Collection
     * 
     * @param  array  $url
     */
    protected function makeUrl(array $url)
    {
        $key = array_keys($url)[0];
        $this->attributes->put($key, $url[$key]);
    }

    /**
     * Get classes for that form
     * 
     * @return string
     */
    protected function getClasses()
    {
        $classes = theme_config('forms.classes.'.$this->name) ?? theme_config('forms.default-classes');
        $classes .= ' form-'.$this->name;
        return $classes;
    }

    /**
     * Marks this form as built, populates the fields with session and sends an event
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

    /**
     * Adds a submit field to this form 
     * 
     * @param string $label
     * @param string $name
     * @return Field
     */
    public function addSubmit($label = 'Submit', $name = 'submit')
    {
        return $this->addField($name, [
            'field' => Submit::class,
            'options' => [
                'label' => $label
            ]
        ]);
    }

    /**
     * Sets/gets an option
     * 
     * @param  string $name
     * @param  mixed $value
     * @return Form|mixed
     */
    public function option(string $name, $value = null)
    {
        if(!is_null($value)){
            $this->options->put($name, $value);
            return $this;
        }
        return $this->options->get($name);
    }

    /**
     * Sets/gets an attribute
     * 
     * @param  string $name
     * @param  mixed $value
     * @return Form|mixed
     */
    public function attribute(string $name, $value = null)
    {
        if(!is_null($value)){
            $this->attributes->put($name, $value);
            return $this;
        }
        return $this->attributes->get($name);
    }

}