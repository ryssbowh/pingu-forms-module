<?php

namespace Pingu\Forms\Events;

use Illuminate\Queue\SerializesModels;
use Pingu\Forms\Contracts\Models\FormableContract;

class EditFormFields
{
    use SerializesModels;

    protected $fields, $object;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(array &$fields, FormableContract $object)
    {
        $this->fields = $fields;
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
