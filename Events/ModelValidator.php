<?php

namespace Pingu\Forms\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Validation\Validator;
use Pingu\Forms\Contracts\Models\FormableContract;

class ModelValidator
{
    use SerializesModels;

    public $validator,$object;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Validator $validator, FormableContract $object)
    {
        $this->validator = $validator;
        $this->object = $object;
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
