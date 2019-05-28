<?php

namespace Pingu\Forms\Events;

use Illuminate\Queue\SerializesModels;

class EditFields
{
    use SerializesModels;

    protected $fields, $model;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(array &$fields, string $model)
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
