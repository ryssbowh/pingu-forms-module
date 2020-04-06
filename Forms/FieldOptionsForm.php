<?php

namespace Pingu\Forms\Forms;

use Pingu\Forms\Support\FieldOptions;
use Pingu\Forms\Support\Fields\Hidden;
use Pingu\Forms\Support\Fields\NumberInput;
use Pingu\Forms\Support\Fields\Submit;
use Pingu\Forms\Support\Form;

class FieldOptionsForm extends Form
{
    protected $action;
    protected $fieldOptions;

    /**
     * @inheritDoc
     */
    public function __construct(array $action, FieldOptions $options)
    {
        $this->action = $action;
        $this->fieldOptions = $options;
        parent::__construct();
    }

    /**
     * @inheritDoc
     */
    public function elements(): array
    {
        $fields = $this->fieldOptions->toFormElements();
        $fields[] = new Submit('_submit');
        return $fields;
    }

    /**
     * @inheritDoc
     */
    public function method(): string
    {
        return 'POST';
    }

    /**
     * @inheritDoc
     */
    public function autocompletes(): string
    {
        return 'off';
    }

    /**
     * Url for this form, valid values are
     * ['url' => '/foo.bar']
     * ['route' => 'login']
     * ['action' => 'MyController@action']
     * 
     * @return array
     * 
     * @see https://github.com/LaravelCollective/docs/blob/5.6/html.md
     */
    public function action(): array
    {
        return $this->action;
    }

    /**
     * Name for this form, ideally it would be application unique, 
     * best to prefix it with the name of the module it's for.
     * only alphanumeric and hyphens
     * 
     * @return string
     */
    public function name(): string
    {
        return 'edit-form-layout-options';
    }
}