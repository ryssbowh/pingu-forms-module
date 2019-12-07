<?php 

namespace Pingu\Forms\Support;

use Pingu\Core\Entities\BaseModel;
use Pingu\Forms\Support\Form;

abstract class FormRepository
{
    protected $model;
        
    /**
     * Constructor
     * 
     * @param BaseModel $model
     */
    public function __construct(BaseModel $model)
    {
        $this->model = $model;
    }

    /**
     * Create form
     * 
     * @param array $action
     * 
     * @return Form
     */
    abstract public function create(array $action): Form;

    /**
     * Edit form
     * 
     * @param array $action
     * 
     * @return Form
     */
    abstract public function edit(array $action): Form;

    /**
     * Delete form
     * 
     * @param array $action
     * 
     * @return Form
     */
    abstract public function delete(array $action): Form;
}