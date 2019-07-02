<?php

namespace Pingu\Forms\Events;

use Illuminate\Queue\SerializesModels;
use Pingu\Core\Entities\BaseModel;

class ModelValidationMessages
{
    use SerializesModels;

    protected $messages, $model;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(array &$messages, BaseModel $model)
    {
        $this->messages = $messages;
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
