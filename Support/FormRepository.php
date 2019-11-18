<?php 

namespace Pingu\Forms\Support;

use Pingu\Core\Entities\BaseModel;
use Pingu\Forms\Support\Form;

abstract class FormRepository
{
    protected $model;
    
    public function __construct(BaseModel $model)
    {
        $this->model = $model;
    }

    abstract public function create(array $action): Form;

    abstract public function edit(array $action): Form;

    abstract public function delete(array $action): Form;
}