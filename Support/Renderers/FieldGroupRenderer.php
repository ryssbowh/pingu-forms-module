<?php

namespace Pingu\Forms\Support\Renderers;

use Pingu\Core\Support\Renderer;
use Pingu\Forms\Support\FieldGroup;
use Pingu\Forms\Support\Form;

class FieldGroupRenderer extends Renderer
{
    public function __construct(FieldGroup $group)
    {
        parent::__construct($group);
    }

    /**
     * @inheritDoc
     */
    public function identifier(): string
    {
        return 'formFieldGroup';
    }

    /**
     * @inheritDoc
     */
    public function objectIdentifier(): string
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public function getHookData(): array
    {
        return [$this->object, $this];
    }

    /**
     * @inheritDoc
     */
    public function getDefaultData(): array
    {
        return [
            'form' => $this->getForm(),
        ];
    }

    /**
     * @inheritDoc
     */
    protected function getFinalData(): array
    {
        return [
            'classes' => $this->object->classes->get(true),
            'labelClasses' => $this->object->labelClasses->get(true),
            'fields' => $this->object->getFields(),
            'group' => $this->object
        ];
    }

    /**
     * @inheritDoc
     */
    public function getDefaultViews(): array
    {
        $form = $this->getForm()->getName();
        return [
            'forms.field-groups.field-group_'.$form.'_'.$this->object->getName(),
            'forms.field-groups.field-group_'.$form,
            'forms.field-groups.field-group',
            $this->object->getDefaultViewName()
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