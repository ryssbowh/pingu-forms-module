<?php

namespace Pingu\Forms\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Pingu\Forms\Contracts\Models\FormableContract;

class ModelFieldDefinitions
{
    use SerializesModels;

    protected $definitions, $object;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Collection $definitions, FormableContract $object)
    {
        $this->definitions = $definitions;
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
