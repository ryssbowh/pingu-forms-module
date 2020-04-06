<?php

namespace Pingu\Forms\Renderers;

use Illuminate\Support\Collection;
use Pingu\Core\Support\Renderers\ObjectRenderer;
use Pingu\Forms\Support\AttributeBag;
use Pingu\Forms\Support\ClassBag;
use Pingu\Forms\Support\Form;

class FormRenderer extends ObjectRenderer
{
    public function __construct(Form $form)
    {
        parent::__construct($form);
    }

    /**
     * @inheritDoc
     */
    protected function viewFolder(): string
    {
        return 'forms';
    }

    /**
     * @inheritDoc
     */
    protected function viewIdentifier(): string
    {
        return 'form';
    }

    /**
     * @inheritDoc
     */
    public function getHookName(): string
    {
        return 'form';
    }

    /**
     * @inheritDoc
     */
    public function getDefaultData(): Collection
    {
        return collect([
            'attributes' => $this->getAttributes(),
            'classes' => $this->getDefaultClasses(),
            'form' => $this->object,
            'groups' => $this->object->getGroups(),
            'elements' => $this->object->getElements(),
            'hasGroups' => $this->object->hasGroups(),
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function afterThemeHook()
    {
        \FormFacade::considerRequest();
        $this->data['attributes']['class'] = $this->data['classes']->toHtml();
    }

    /**
     * Form attributes
     * 
     * @return AttributeBag
     */
    protected function getAttributes(): AttributeBag
    {
        return new AttributeBag([
                'method' => $this->object->method(),
                'files' => $this->object->acceptsFiles(),
                'id' => 'form-'.$this->object->getName(),
                'autocomplete' => $this->object->autocompletes()
            ] + $this->object->action()
        );
    }

    /**
     * Default classes for a form
     * 
     * @return ClassBag
     */
    protected function getDefaultClasses(): ClassBag
    {
        return new ClassBag([
            'form',
            'form-'.$this->object->getName()
        ]);
    }

    /**
     * Form getter
     * 
     * @return Form
     */
    public function getForm(): Form
    {
        return $this->object;
    }
}