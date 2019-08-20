<?php

namespace Pingu\Forms\Events;

use Illuminate\Queue\SerializesModels;
use Pingu\Forms\Contracts\Models\FormableContract;

class ModelValidationRules
{
    use SerializesModels;

    protected $rules, $object;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(array &$rules, FormableContract $object)
    {
        $this->rules = $rules;
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
