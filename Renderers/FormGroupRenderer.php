<?php

namespace Pingu\Forms\Renderers;

use Illuminate\Support\Collection;
use Pingu\Core\Support\Renderers\ObjectRenderer;
use Pingu\Forms\Support\ClassBag;
use Pingu\Forms\Support\Form;
use Pingu\Forms\Support\FormGroup;

class FormGroupRenderer extends ObjectRenderer
{
    public function __construct(FormGroup $group)
    {
        parent::__construct($group);
    }

    /**
     * @inheritDoc
     */
    public function viewFolder(): string
    {
        return 'forms.form-groups';
    }

    /**
     * @inheritDoc
     */
    public function getDefaultData(): Collection
    {
        return collect([
            'classes' => new ClassBag([
                'form-element',
                'form-group',
                'form-group-'.$this->object->getName()
            ]),
            'labelClasses' => new ClassBag([
                'form-element-label',
                'form-group-label',
                'form-group-label-'.$this->object->getName()
            ]),
            'fields' => $this->object->getFields(),
            'group' => $this->object,
            'form' => $this->getForm()
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getHookName(): string
    {
        return 'formGroup';
    }

    /**
     * @inheritDoc
     */
    protected function getDefaultViews(): array
    {
        return [
            $this->viewFolder().'.'.$this->viewIdentifier().'_'.$this->getForm()->getViewKey().'_'.$this->viewKey(),
            $this->viewFolder().'.'.$this->viewIdentifier().'_'.$this->viewKey(),
            $this->viewFolder().'.'.$this->viewIdentifier(),
            $this->object->systemView()
        ];
    }

    /**
     * Form getter
     * 
     * @return Form
     */
    public function getForm(): Form
    {
        return $this->object->getForm();
    }
}