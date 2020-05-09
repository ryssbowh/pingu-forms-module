<?php 

namespace Pingu\Forms\Support;

use Pingu\Core\Entities\BaseModel;
use Pingu\Core\Forms\BaseModelCreateForm;
use Pingu\Core\Forms\BaseModelDeleteForm;
use Pingu\Core\Forms\BaseModelEditForm;
use Pingu\Core\Forms\BaseModelFilterForm;
use Pingu\Field\Contracts\HasFieldsContract;
use Pingu\Forms\Contracts\FormRepositoryContract;
use Pingu\Forms\Support\Form;

class BaseForms implements FormRepositoryContract
{   
    /**
     * @inheritDoc
     */
    public function create(array $args): Form
    {
        return new BaseModelCreateForm(...$args);
    }

    /**
     * @inheritDoc
     */
    public function edit(array $args): Form
    {
        return new BaseModelEditForm(...$args);
    }

    /**
     * @inheritDoc
     */
    public function delete(array $args): Form
    {
        return new BaseModelDeleteForm(...$args);
    }

    /**
     * @inheritDoc
     */
    public function filter(array $args): Form
    {
        return new BaseModelFilterForm(...$args);
    }
}