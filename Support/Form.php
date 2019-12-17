<?php
namespace Pingu\Forms\Support;

use Pingu\Forms\Support\ClassBag;
use Pingu\Forms\Traits\HasAttributesFromOptions;
use Pingu\Forms\Traits\HasFormElements;
use Pingu\Forms\Traits\HasGroups;
use Pingu\Forms\Traits\HasOptions;
use Pingu\Forms\Traits\RendersForm;

abstract class Form
{
    use RendersForm, HasFormElements, HasOptions, HasGroups, HasAttributesFromOptions;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var ClassBag
     */
    public $classes;

    protected $attributeOptions = ['id', 'files', 'method'];

    public function __construct()
    {
        $this->name = $this->makeName($this->name());
        $options = array_merge(
            [
                'method' => $this->method(),
                'files' => true,
                'id' => 'form-'.$this->name
            ], 
            $this->options()
        );
        $this->buildOptions($options);
        $this->setViewSuggestions(
            [
            'forms.form-'.$this->name,
            'forms.form',
            'forms::form'
            ]
        );
        $this->classes = new ClassBag(
            [
            'form',
            'form-'.$this->name
            ]
        );
        $this->makeElements($this->elements());
        $this->makeGroups($this->groups());
        $this->afterBuilt();
    }

    /**
     * Name of that field. all non alphanumeric character will be removed.
     * (hyphens will be kept)
     * 
     * @return string
     */
    protected abstract function name(): string;

    /**
     * Method for that form (PUT POST GET DELETE PATCH
     * 
     * @return string
     */
    protected abstract function method(): string;

    /**
     * Url for that form, can be an url, an action or a route
     *
     * @see    https://github.com/LaravelCollective/docs/blob/5.6/html.md#opening-a-form
     * @return array
     */
    protected abstract function action(): array;

    /**
     * Fields definitions
     * 
     * @return array
     */
    protected abstract function elements(): array;

    /**
     * Takes the get parameters of a request and adds them as hidden fields
     *
     * @param  ?array $only
     * @return Pingu\Forms\Support\Form
     */
    public function considerGet(?array $only = null)
    {
        if($input = request()->input()) {
            $input = is_null($only) ? $input : array_intersect_key($input, array_flip($only));
            foreach ($input as $param => $value) {
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
        $this->classes->add('js-ajax-form');
        return $this;
    }

    /**
     * Removes non-wanted characters from name
     * 
     * @param  string $name
     * @return string
     */
    protected function makeName(string $name): string
    {
        return preg_replace('/[^A-Za-z0-9\-]/i', '', $name);
    }

    /**
     * Name getter
     * 
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    public function fieldsOutsideOfGroups()
    {
        $out = [];
        foreach ($this->elements as $name => $element) {
            if (!$this->searchFieldGroup($name)) {
                $out[] = $name;
            }
        }
        return $out;
    }

    /**
     * Adds a hidden field to this form
     * 
     * @param  string      $name
     * @param  mixed value
     * @return Form
     */
    public function addHiddenField(string $name, $value)
    {
        $this->addField(new Hidden($name, ['default' => $value]));
        $this->moveElementUp($name);
        return $this;
    }

    /**
     * Adds a submit field to this form 
     * 
     * @param  string $label
     * @param  string $name
     * @return Form
     */
    public function addSubmit(string $label = 'Submit', string $name = '_submit')
    {
        $this->addElement(new Submit($name, ['label' => $label]));
        return $this;
    }

    /**
     * Adds a delete button to this form
     *
     * @param string $label
     * @param string $field
     */
    public function addDeleteButton(string $url, string $label = "Delete", string $field = '_delete')
    {
        $this->addElement(new Link($field, ['label' => $label, 'url' => $url], ['class' => 'delete']));
        return $this;
    }

    /**
     * Adds a back button to this form
     * 
     * @param  string      $label
     * @param  string|null $url 
     * @param  string      $field
     * @return Form
     */
    public function addBackButton(string $label = "Back", ?string $url = null, string $field = '_back')
    {
        if(is_null($url)) {
            $url = url()->previous();
        }
        $this->addElement(new Link($field, ['label' => $label, 'url' => $url], ['class' => 'back']));
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
        $this->addElement(new Hidden($name, ['default' => $field->getValue()]));
        return $this;
    }

    /**
     * moves the action into the attributes Collection
     * 
     * @param array $url
     */
    protected function makeAction(array $url)
    {
        $key = array_keys($url)[0];
        $this->options->put($key, $url[$key]);
    }

    /**
     * Called at the end of constructor
     */
    protected function afterBuilt()
    {
    }

    /**
     * Options for the form
     * 
     * @return array
     */
    protected function options(): array
    {
        return [];
    }

    /**
     * Groups for the form
     * 
     * @return array
     */
    protected function groups(): array
    {
        return [];
    }
    
}