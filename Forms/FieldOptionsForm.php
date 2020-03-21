<?php

namespace Pingu\Forms\Forms;

use Pingu\Forms\Support\FieldOptions;
use Pingu\Forms\Support\Fields\NumberInput;
use Pingu\Forms\Support\Fields\Submit;
use Pingu\Forms\Support\Form;

class FieldOptionsForm extends Form
{
    protected $action;
    protected $fieldOptions;

    /**
     * Bring variables in your form through the constructor :
     */
    public function __construct(array $action, FieldOptions $options)
    {
        $this->action = $action;
        $this->fieldOptions = $options;
        parent::__construct();
    }

    /**
     * Fields definitions for this form, classes used here
     * must extend Pingu\Forms\Support\Field
     * 
     * @return array
     */
    public function elements(): array
    {
        $fields = $this->fieldOptions->toFormElements();
        $fields [] = new Submit('_submit');
        return $fields;
    }

    /**
     * Method for this form, POST GET DELETE PATCH and PUT are valid
     * 
     * @return string
     */
    public function method(): string
    {
        return 'POST';
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

    public function afterBuilt()
    {
        // dump($this);
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