<?php
namespace Pingu\Forms\Traits;

use Pingu\Forms\Events\FormBuilt;
use Pingu\Forms\Exceptions\FormException;
use Pingu\Forms\Support\AttributeBag;
use Pingu\Forms\Support\ClassBag;
use Pingu\Forms\Support\Fields\Hidden;
use Pingu\Forms\Support\Fields\Link;
use Pingu\Forms\Support\Fields\Submit;

trait Form
{
    use RendersForm, HasFormElements, HasOptions, HasAttributes;

    public $classes;
    protected $name;

    public function __construct()
    {
        $this->name = $this->makeName($this->name());
        $this->buildOptions($this->options());
        $this->buildAttributes(
            array_merge($this->attributes(), [
                'method' => $this->method(),
                'files' => true,
                'id' => 'form-'.$this->name,
            ])
        );
        $this->setViewSuggestions([
            'forms.form-'.$this->name,
            'forms.form',
            'forms::form'
        ]);
        $this->classes = new ClassBag([
            'form',
            'form-'.$this->name
        ]);
        $this->makeAction($this->action());
        $this->makeElements($this->elements());
        $this->afterBuilt();
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

    /**
     * moves the action into the attributes Collection
     * 
     * @param array  $url
     */
    protected function makeAction(array $url)
    {
        $key = array_keys($url)[0];
        $this->attributes->put($key, $url[$key]);
    }

    protected function afterBuilt()
    {}

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