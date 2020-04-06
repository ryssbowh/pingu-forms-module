<?php

namespace Pingu\Forms\Renderers;

use Illuminate\Support\Collection;
use Pingu\Core\Support\Renderers\ObjectRenderer;
use Pingu\Forms\Support\ClassBag;
use Pingu\Forms\Support\FieldGroup;
use Pingu\Forms\Support\Form;

class FieldGroupRenderer extends ObjectRenderer
{
    public function __construct(FieldGroup $group)
    {
        parent::__construct($group);
    }

    /**
     * @inheritDoc
     */
    public function viewFolder(): string
    {
        return 'forms.field-groups';
    }

    /**
     * @inheritDoc
     */
    public function getHookName(): string
    {
        return 'formFieldGroup';
    }

    /**
     * @inheritDoc
     */
    public function getDefaultData(): Collection
    {
        return collect([
            'classes' => new ClassBag([
                'form-element',
                'field-group',
                'field-group-'.$this->object->getName(),
                'field-group-type-'.class_machine_name($this->object->getFields()[0])
            ]),
            'labelClasses' => new ClassBag([
                'form-element-label',
                'field-group-label',
                'field-group-label-'.$this->object->getName()
            ]),
            'fields' => $this->object->getFields(),
            'group' => $this->object,
            'form' => $this->getForm()
        ]);
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