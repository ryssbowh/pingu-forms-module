<?php

namespace Pingu\Forms\Support\Renderers;

use Pingu\Core\Support\Renderer;
use Pingu\Forms\Support\Field;
use Pingu\Forms\Support\Form;

class FormFieldRenderer extends Renderer
{
    public function __construct(Field $field)
    {
        parent::__construct($field);
    }

    /**
     * @inheritDoc
     */
    public function identifier(): string
    {
        return 'formField';
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
            'form' => $this->getForm(),
        ];
    }

    /**
     * @inheritDoc
     */
    protected function getFinalData(): array
    {
        $attributes = $this->object->buildAttributes();
        $attributes['class'] = $this->object->classes->get(true);
        return [
            'field' => $this->object,
            'wrapperClasses' => $this->object->wrapperClasses->get(true),
            'attributes' => $attributes,
            'labelClasses' => $this->object->labelClasses->get(true),
        ];
    }

    /**
     * @inheritDoc
     */
    public function getDefaultViews(): array
    {
        $type = $this->object->getType();
        $form = $this->object->getForm()->getName();
        $name = $this->object->getName();
        return [
            'forms.fields.field_form-'.$form.'_'.$name,
            'forms.fields.field_form-'.$form.'_'.$type,
            'forms.fields.field_'.$type.'_'.$name,
            'forms.fields.field_'.$type,
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