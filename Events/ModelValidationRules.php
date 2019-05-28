<?php

namespace Pingu\Forms\Events;

use Illuminate\Queue\SerializesModels;

class ModelValidationRules
{
    use SerializesModels;

    protected $rules, $model;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(array &$rules, string $model)
    {
        $this->rules = $rules;
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
