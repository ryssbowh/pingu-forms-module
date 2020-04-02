<?php

namespace Pingu\Forms\Support\Renderers;

use Pingu\Core\Support\Renderer;
use Pingu\Forms\Support\Form;
use Pingu\Forms\Support\FormGroup;

class FormGroupRenderer extends Renderer
{
    public function __construct(FormGroup $group)
    {
        parent::__construct($group);
    }

    /**
     * @inheritDoc
     */
    public function identifier(): string
    {
        return 'formGroup';
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
        return [$this->object, $this->getForm(), $this];
    }

    /**
     * @inheritDoc
     */
    public function getDefaultData(): array
    {
        return [
            'form' => $this->object->getForm(),
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
            'group' => $this->object,
            'form' => $this->getForm()
        ];
    }

    /**
     * @inheritDoc
     */
    public function getDefaultViews(): array
    {
        $form = $this->getForm()->getName();
        return [
            'forms.form-groups.form-group_'.$form.'_'.$this->object->getName(),
            'forms.form-groups.form-group_'.$form,
            'forms.form-groups.form-group',
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