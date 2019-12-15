<?php

namespace Pingu\Forms\Facades;

use Illuminate\Support\Facades\Facade;

class FormField extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'forms.field';
    }

}