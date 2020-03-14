<?php

namespace Pingu\Forms\Contracts;

use Pingu\Forms\Support\Form;

interface FormRepositoryContract
{
    /**
     * Create form
     * 
     * @param array $action
     * 
     * @return Form
     */
    public function create(array $action): Form;

    /**
     * Edit form
     * 
     * @param array $action
     * 
     * @return Form
     */
    public function edit(array $action): Form;

    /**
     * Delete form
     * 
     * @param array $action
     * 
     * @return Form
     */
    public function delete(array $action): Form;

}