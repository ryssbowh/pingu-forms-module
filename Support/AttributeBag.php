<?php

namespace Pingu\Forms\Support;

use Illuminate\Support\Collection;

class AttributeBag extends Collection
{
    public function toHtml()
    {
        $out = ' ';
        foreach ($this->items as $name => $value) {
            $out .= $name.'="'.$value.'" ';
        }
        return $out;
    }
}