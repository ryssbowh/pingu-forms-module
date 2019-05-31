<?php

namespace Pingu\Forms\Events;

use Illuminate\Queue\SerializesModels;
use Pingu\Core\Entities\BaseModel;
use Illuminate\Validation\Validator;

class ModelValidator
{
    use SerializesModels;

    public $validator,$model;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Validator $validator, BaseModel $model)
    {
        $this->validator = $validator;
        $this->model = $model;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}