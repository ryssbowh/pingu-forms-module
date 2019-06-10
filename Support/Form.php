<?php

namespace Pingu\Forms\Support;

use Pingu\Core\Contracts\RenderableWithSuggestions;
use Pingu\Forms\Contracts\FormContract;
use Pingu\Forms\Contracts\HasFieldsContract;
use Pingu\Forms\Traits\Form as FormTrait;

abstract class Form implements HasFieldsContract, RenderableWithSuggestions
{
	use FormTrait;

    protected abstract function name();

    protected abstract function method();

	protected abstract function url();

	protected abstract function fields();

	protected function attributes()
    {
        return [];
    }

    protected function id()
    {
        return $this->name();
    }

    protected function options()
    {
        return [];
    }

    protected function groups()
    {
        return [
            'default' => $this->getFieldNames()
        ];
    }
    
}