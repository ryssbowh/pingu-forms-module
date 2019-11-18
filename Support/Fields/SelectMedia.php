<?php

namespace Pingu\Forms\Support\Fields;

use Pingu\Forms\Support\Field;
use Pingu\Media\Entities\Media;

class SelectMedia extends Field
{
    public function getMedia()
    {
        return Media::find($this->value);
    }
}