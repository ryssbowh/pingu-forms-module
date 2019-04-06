<?php

namespace Modules\Forms\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Forms\Components\Form;

class FormBuilt
{
    use SerializesModels;

    public $form,$name;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(string $name, Form $form)
    {
        $this->name = $name;
        $this->form = $form;
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
