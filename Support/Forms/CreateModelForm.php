<?php

namespace Pingu\Forms\Support\Forms;

use Pingu\Forms\Support\Form;

abstract class CreateModelForm extends EditModelForm
{
    /**
     * @inheritDoc
     */
    public function method(): string
    {
        return 'POST';
    }
}