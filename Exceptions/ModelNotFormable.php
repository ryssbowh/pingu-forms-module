<?php

namespace Pingu\Forms\Exceptions;

use Pingu\Core\Entities\BaseModel;
use Pingu\Forms\Contracts\Models\FormableContract;

class ModelNotFormable extends \Exception
{

    public function __construct(BaseModel $model)
    {
        $message = get_class($model)." doesn't implement ".FormableContract::class;
        parent::__construct($message);
    }

}