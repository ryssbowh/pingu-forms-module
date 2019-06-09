<?php

namespace Pingu\Forms\Support;

use Pingu\Forms\Contracts\FormContract;
use Pingu\Forms\Traits\Form as FormTrait;
use Pingu\Forms\Traits\HasFields;

abstract class Form implements FormContract
{
	use FormTrait, HasFields;

	protected function makeFields()
    {
        $this->fields = collect();
        $this->addFields($this->fields());
    }
    
}