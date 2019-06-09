<?php
namespace Pingu\Forms\Traits;

use Pingu\Forms\Events\FormBuilt;
use Pingu\Forms\Support\ActionBag;
use Pingu\Forms\Support\AttributeBag;
use Pingu\Forms\Support\ClassBag;
use Pingu\Forms\Traits\HasGroups;
use Pingu\Forms\Traits\RendersForm;

trait Form
{
    use HasGroups, RendersForm;

    protected $classes;
    protected $attributes;
    protected $actions;

    public function __construct()
    {
        $this->classes = new ClassBag($this->getClasses());
        $this->attributes = new AttributeBag(array_merge(
            $this->attributes(),
            ['method' => $this->method(),
            'files' => true
        ]));
        $this->makeUrl();
        $this->actions = new ActionBag($this->actions());
        $this->makeFields();
        $this->makeGroups($this->getFieldNames(), $this->groups());
    }

    public function getName()
    {
        return $this->name;
    }

    public function attributes()
    {
        return [];
    }

    public function groups()
    {
        return [];
    }

    public function actions()
    {
        return [];
    }

    protected function makeUrl()
    {
        $key = array_keys($this->url())[0];
        $this->attributes->add($key, $this->url()[$key]);
    }

    protected function getClasses()
    {
        $classes = theme_config('forms.classes.'.$this->getName()) ??
            theme_config('forms.default-classes').' form-'.$this->getName();

        return $classes;
    }

    public function addAction(string $name, string $type, string $label, array $attributes= [])
    {
        $action = [
            'type' => $type,
            'label' => $label,
            'attributes' => $attributes
        ];
        $this->actions->add($name, $action);
    }

    /**
     * Marks this form as built, populates the fields if applicable and sends an event
     *
     * @return Form
     */
    public function end()
    {   
        \FormFacade::considerRequest();
        event(new FormBuilt($this->getName(), $this));
        return $this;
    }

}