<?php

namespace Pingu\Forms\Contracts;

use Pingu\Forms\Support\Form;

interface HasFormsContract
{
    /**
     * Forms class for this entity
     * 
     * @return EntityFormRepositoryContract
     */
    public static function forms(): FormRepositoryContract;
}