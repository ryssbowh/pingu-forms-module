<?php

namespace Pingu\Forms\Renderers;

use Illuminate\Support\Collection;
use Pingu\Core\Support\Renderers\ObjectRenderer;
use Pingu\Forms\Support\AttributeBag;
use Pingu\Forms\Support\ClassBag;
use Pingu\Forms\Support\Field;
use Pingu\Forms\Support\Form;

class FormFieldRenderer extends ObjectRenderer
{
    public function __construct(Field $field)
    {
        parent::__construct($field);
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

    /**
     * @inheritDoc
     */
    public function getHookName(): string
    {
        return 'formField';
    }

    /**
     * @inheritDoc
     */
    protected function viewFolder(): string
    {
        return 'forms.fields';
    }

    /**
     * @inheritDoc
     */
    protected function getDefaultData(): Collection
    {
        return collect([
            'field' => $this->object,
            'classes' => $this->getClasses(),
            'attributes' => $this->getAttributes(),
            'labelClasses' => $this->getLabelClasses(),
            'wrapperClasses' => $this->getWrapperClasses()
        ]);
    }

    /**
     * Field's classes
     * 
     * @return ClassBag
     */
    protected function getClasses(): ClassBag
    {
        return new ClassBag([
            'field',
            'field-'.$this->object->getName(),
            'field-'.$this->object->getViewKey()
        ]);
    }

    /**
     * Field's label classes
     * 
     * @return ClassBag
     */
    protected function getLabelClasses(): ClassBag
    {
        return new ClassBag([
            'field-label',
            'field-label-'.$this->object->getName(),
            'field-label-'.$this->object->getViewKey(),
        ]);
    }

    /**
     * Field's wrapper classes
     * 
     * @return ClassBag
     */
    protected function getWrapperClasses(): ClassBag
    {
        return new ClassBag([
            'field-wrapper',
            'form-element',
            'field-wrapper-'.$this->object->getName(),
            'field-wrapper-type-'.$this->object->getViewKey(),
        ]);
    }

    /**
     * Field's attributes
     * 
     * @return AttributeBag
     */
    protected function getAttributes(): AttributeBag
    {
        return new AttributeBag([
            'required' => $this->object->option('required'),
            'id' => $this->object->option('id')
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function afterThemeHook()
    {
        $this->data->get('attributes')->put('class', $this->data->get('classes')->toHtml());
    }

    /**
     * @inheritDoc
     */
    protected function getDefaultViews(): array
    {
        return [
            $this->viewFolder().'.'.$this->getForm()->getViewKey().'_'.$this->viewKey(),
            $this->viewFolder().'.'.$this->viewKey(),
            $this->object->systemView()
        ];
    }
}