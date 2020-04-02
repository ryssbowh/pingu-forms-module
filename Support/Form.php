<?php

namespace Pingu\Forms\Support;

use Pingu\Core\Contracts\RenderableContract;
use Pingu\Core\Contracts\RendererContract;
use Pingu\Core\Traits\RendersWithRenderer;
use Pingu\Forms\Events\FormBuilt;
use Pingu\Forms\Support\ClassBag;
use Pingu\Forms\Support\Renderers\FormRenderer;
use Pingu\Forms\Traits\HasAttributesFromOptions;
use Pingu\Forms\Traits\HasFormElements;
use Pingu\Forms\Traits\HasGroups;
use Pingu\Forms\Traits\HasOptions;

abstract class Form implements RenderableContract
{
    use HasFormElements, HasOptions, HasGroups, HasAttributesFromOptions, RendersWithRenderer;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var ClassBag
     */
    public $classes;

    protected $attributeOptions = ['id', 'files', 'method', 'autocomplete'];

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
        $this->classes = new ClassBag($this->defaultClasses());
        $this->makeElements($this->elements());
        $this->makeGroups($this->groups());
        $this->afterBuilt();
        event(new FormBuilt($this->getName(), $this));
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
    public abstract function action(): array;

    /**
     * Fields definitions
     * 
     * @return array
     */
    protected abstract function elements(): array;

    /**
     * Class to render this form
     * 
     * @return RendererContract
     */
    public function getRenderer(): RendererContract
    {
        return new FormRenderer($this);
    }

    /**
     * Takes the get parameters of a request and adds them as hidden fields
     *
     * @param  ?array $only
     * @return Pingu\Forms\Support\Form
     */
    public function considerGet(?array $only = null)
    {
        if ($input = request()->input()) {
            $input = is_null($only) ? $input : array_intersect_key($input, array_flip($only));
            foreach ($input as $param => $value) {
                $this->addHiddenField($param, $value);
            }
        }
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

    /**
     * All fields that are not in a group
     * 
     * @return array
     */
    public function fieldsOutsideOfGroups()
    {
        $inGroups = $this->allFieldInGroups();
        $allElements = $this->getElementNames();
        return array_diff($allElements, $inGroups);
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
        if (is_null($url)) {
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
     * Default classes
     * 
     * @return array
     */
    protected function defaultClasses(): array
    {
        return [
            'form',
            'form-'.$this->name
        ];
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