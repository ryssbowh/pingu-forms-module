<?php
namespace Pingu\Forms\Forms;

use Illuminate\Support\Str;
use Pingu\Forms\Support\Fields\Submit;

class BaseModelDeleteForm extends BaseModelCreateForm
{
    /**
     * Fields definitions for this form, classes used here
     * must extend Pingu\Forms\Support\Field
     * 
     * @return array
     */
    public function elements(): array
    {
        return [new Submit('_submit')];
    }

    /**
     * Method for this form, POST GET DELETE PATCH and PUT are valid
     * 
     * @return string
     */
    public function method(): string
    {
        return 'DELETE';
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
        return 'delete-model-'.class_machine_name($this->model);
    }
}