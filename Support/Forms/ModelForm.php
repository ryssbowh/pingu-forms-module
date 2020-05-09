<?php

namespace Pingu\Forms\Support\Forms;

use Illuminate\Support\Collection;
use Pingu\Core\Entities\BaseModel;
use Pingu\Forms\Support\Fields\Submit;
use Pingu\Forms\Support\Form;

abstract class ModelForm extends Form
{
    /**
     * @var BaseModel
     */
    protected $model;

    /**
     * @var array
     */
    protected $action;

    public function __construct(BaseModel $model, array $action)
    {
        $this->model = $model;
        $this->action = $action;
        parent::__construct();
    }

    /**
     * @inheritDoc
     */
    public function action(): array
    {
        return $this->action;
    }

    /**
     * @inheritDoc
     */
    public function elements(): array
    {
        $fields = $this->createElementsFromModel();
        $fields[] = new Submit('_submit');
        return $fields;
    }

    /**
     * Retrieve the fields to turn into form fields from the model
     * 
     * @return Collection
     */
    protected function getModelFields(): Collection
    {
        return $this->model->fieldRepository()->all();
    }

    /**
     * Creates form elements from a model
     * 
     * @return array
     */
    protected function createElementsFromModel(): array
    {
        $model = $this->model;
        return $this->getModelFields()->map(function ($field) use ($model) {
            $value = $field->formValue($model);
            return $field->toFormElement($value);
        })->all();
    }
    
}