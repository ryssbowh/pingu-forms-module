<?php 

namespace Pingu\Forms\Support;

use Pingu\Core\Entities\BaseModel;
use Pingu\Forms\Contracts\FormRepositoryContract;
use Pingu\Forms\Forms\BaseModelCreateForm;
use Pingu\Forms\Forms\BaseModelDeleteForm;
use Pingu\Forms\Forms\BaseModelEditForm;
use Pingu\Forms\Support\Form;

class BaseForms implements FormRepositoryContract
{   
    /**
     * @var BaseModel
     */
    protected $model;

    public function __construct(BaseModel $model)
    {
        $this->model = $model;
    }

    /**
     * @inheritDoc
     */
    public function create(array $args): Form
    {
        return new BaseModelCreateForm($this->model, ...$args);
    }

    /**
     * @inheritDoc
     */
    public function edit(array $args): Form
    {
        return new BaseModelEditForm($this->model, ...$args);
    }

    /**
     * @inheritDoc
     */
    public function delete(array $args): Form
    {
        return new BaseModelDeleteForm($this->model, ...$args);
    }
}