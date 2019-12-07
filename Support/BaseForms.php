<?php 

namespace Pingu\Forms\Support;

use Pingu\Forms\Forms\BaseModelCreateForm;
use Pingu\Forms\Forms\BaseModelDeleteForm;
use Pingu\Forms\Forms\BaseModelEditForm;
use Pingu\Forms\Support\Form;

class BaseForms extends FormRepository
{
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