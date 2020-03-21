<?php
namespace Pingu\Forms\Forms;

use Illuminate\Support\Str;
use Pingu\Core\Entities\BaseModel;
use Pingu\Entity\Contracts\BundleContract;
use Pingu\Forms\Support\Fields\Submit;
use Pingu\Forms\Support\Form;

class BaseModelCreateForm extends Form
{
    protected $action;
    protected $model;
    protected $updating = false;

    /**
     * Bring variables in your form through the constructor :
     */
    public function __construct(BaseModel $model, array $action)
    {
        $this->action = $action;
        $this->model = $model;
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
        $fields = $this->model->fields()->toFormElements($this->model, $this->updating);
        $fields['_submit'] = new Submit('_submit');
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

    /**
     * Name for this form, ideally it would be application unique, 
     * best to prefix it with the name of the module it's for.
     * only alphanumeric and hyphens
     * 
     * @return string
     */
    public function name(): string
    {
        return 'create-model-'.class_machine_name($this->model);
    }
}