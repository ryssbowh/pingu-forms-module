<?php
namespace Pingu\Forms\Traits;

use Pingu\Forms\Events\FormBuilt;
use Pingu\Forms\Exceptions\FormException;
use Pingu\Forms\Support\ClassBag;
use Pingu\Forms\Support\Fields\Hidden;
use Pingu\Forms\Support\Fields\Link;
use Pingu\Forms\Support\Fields\Submit;
use Pingu\Forms\Traits\HasFields;
use Pingu\Forms\Traits\RendersForm;

trait Form
{
    use RendersForm, HasFields, HasClasses;

    public $attributes;
    public $options;
    protected $name;
    protected $built = false;

    public function __construct()
    {
        $this->name = $this->makeName($this->name());
        $this->id = $this->id();
        $this->options = collect($this->options());
        $this->attributes = collect(array_merge($this->attributes(), [
            'method' => $this->method(),
            'files' => true,
            'id' => $this->id(),
        ]));
        $this->addClasses($this->getDefaultClasses());
        $this->makeUrl($this->url());
        $this->makeFields($this->fields());
        $this->makeGroups($this->groups());
        $this->buildViewSuggestions();
    }

    /**
     * Takes the get parameters of a request and adds them as hidden fields
     * @param  ?array $only
     * @return Pingu\Forms\Support\Form
     */
    public function considerGet(?array $only = null)
    {
        if($input = request()->input()){
            $input = is_null($only) ? $input : array_intersect_key($input, array_flip($only));
            foreach($input as $param => $value){
                $this->addHiddenField($param, $value);
            }
        }
    }

    /**
     * Adds the class 'ajax-form' to this form
     * 
     * @return Form
     */
    public function isAjax()
    {
        return $this->addClass('js-ajax-form');
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
     * Get default classes for that form
     * 
     * @return string
     */
    protected function getDefaultClasses()
    {
        $classes = theme_config('forms.classes.'.$this->name) ?? theme_config('forms.default-classes');
        $classes .= ' form form-'.$this->name;
        return $classes;
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

    /**
     * Adds a hidden field to this form
     * 
     * @param string $name
     * @param mixed value
     * @return Form
     */
    public function addHiddenField(string $name, $value)
    {
        $this->addField(new Hidden($name, ['default' => $value]));
        $this->moveFieldUp($name);
        return $this;
    }

    /**
     * Adds a submit field to this form 
     * 
     * @param string $label
     * @param string $name
     * @return Form
     */
    public function addSubmit(string $label = 'Submit', string $name = '_submit')
    {
        $this->addField(new Submit($name, ['label' => $label]));
        return $this;
    }

    /**
     * Adds a delete button to this form
     * @param string $label
     * @param string $field
     */
    public function addDeleteButton(string $url, string $label = "Delete", string $field = '_delete')
    {
        $this->addField(new Link($field, ['label' => $label, 'url' => $url], ['class' => 'delete']));
        return $this;
    }

    /**
     * Adds a back button to this form
     * 
     * @param string      $label
     * @param string|null $url 
     * @param string      $field
     * @return Form
     */
    public function addBackButton(string $label = "Back", ?string $url = null, string $field = '_back')
    {
        if(is_null($url)){
            $url = url()->previous();
        }
        $this->addField(new Link($field, ['label' => $label, 'url' => $url], ['class' => 'back']));
        return $this;
    }

    /**
     * Disables a field
     * 
     * @param  string $name
     * @return Form
     */
    public function disableField(string $name)
    {
        $field = $this->getField($name);
        $this->addField(new Hidden($name, ['default' => $field->getValue()]));
        return $this;
    }
}