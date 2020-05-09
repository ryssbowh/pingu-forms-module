<?php

namespace Pingu\Forms\Support\Forms;

use Pingu\Forms\Support\Form;

abstract class EditModelForm extends ModelForm
{
    /**
     * @inheritDoc
     */
    public function method(): string
    {
        return 'PUT';
    }
}