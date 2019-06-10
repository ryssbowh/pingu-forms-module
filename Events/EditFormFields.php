<?php

namespace Pingu\Forms\Events;

use Illuminate\Queue\SerializesModels;
use Pingu\Core\Entities\BaseModel;

class EditFormFields
{
    use SerializesModels;

    protected $fields, $model;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(array &$fields, BaseModel $model)
    {
        $this->fields = $fields;
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