<?php

namespace Pingu\Forms\Contracts;

use Pingu\Forms\Support\Form;

interface FormRepositoryContract
{
    /**
     * Create form
     * 
     * @param array $args
     * 
     * @return Form
     */
    public function create(array $args): Form;

    /**
     * Edit form
     * 
     * @param array $args
     * 
     * @return Form
     */
    public function edit(array $args): Form;

    /**
     * Delete form
     * 
     * @param array $args
     * 
     * @return Form
     */
    public function delete(array $args): Form;

    /**
     * Filter Form
     *
     * @param array $args
     * 
     * @return Form
     */
    public function filter(array $args): Form;

}