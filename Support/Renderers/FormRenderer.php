<?php

namespace Pingu\Forms\Support\Renderers;

use Pingu\Core\Support\Renderer;
use Pingu\Forms\Support\Form;

class FormRenderer extends Renderer
{
    public function __construct(Form $form)
    {
        parent::__construct($form);
    }

    /**
     * @inheritDoc
     */
    public function identifier(): string
    {
        return 'form';
    }

    /**
     * @inheritDoc
     */
    public function objectIdentifier(): string
    {
        return $this->object->getName();
    }

    /**
     * @inheritDoc
     */
    public function getHookData(): array
    {
        return [$this->object->getName(), $this->object, $this];
    }

    /**
     * @inheritDoc
     */
    public function getDefaultData(): array
    {
        return [
            'form' => $this->object,
            'groups' => $this->object->getGroups(),
            'elements' => $this->object->getElements(),
            'hasGroups' => $this->object->hasGroups(),
        ];
    }

    /**
     * @inheritDoc
     */
    protected function getFinalData(): array
    {
        $data = $this->getData();
        $attributes = array_merge(
            $this->object->buildAttributes()->toArray(), 
            $this->object->action()
        );
        $attributes['class'] = $this->object->classes->get(true);
        $data['attributes'] = $attributes;
        return $data;
    }

    /**
     * @inheritDoc
     */
    public function getDefaultViews(): array
    {
        return [
            'forms.forms.form_'.$this->object->getName(),
            'forms.forms.form',
            'forms@form'
        ];
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

    /**
     * @inheritDoc
     */
    protected function beforeRendering()
    {
        \FormFacade::considerRequest();
    }
}