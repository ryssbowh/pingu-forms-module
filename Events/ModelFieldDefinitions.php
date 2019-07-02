<?php

namespace Pingu\Forms\Events;

use Illuminate\Queue\SerializesModels;
use Pingu\Core\Entities\BaseModel;

class ModelFieldDefinitions
{
    use SerializesModels;

    protected $definitions, $model;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(array &$definitions, BaseModel $model)
    {
        $this->definitions = $definitions;
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
