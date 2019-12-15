<?php

namespace Pingu\Forms\Traits;

use Pingu\Core\Traits\RendersWithSuggestions;
use Pingu\Forms\Events\FormBuilt;
use Pingu\Forms\Exceptions\FormException;

trait RendersForm
{
    use RendersWithSuggestions;

    /**
     * print form's opening
     *
     * @see    https://laravelcollective.com/docs/5.4/html
     * @throws FormNotBuiltException
     * @return void
     */
    public function renderStart()
    {
        \FormFacade::considerRequest();
        event(new FormBuilt($this->getName(), $this));
        $attributes = array_merge(
            $this->buildAttributes()->toArray(), 
            $this->action()
        );
        $attributes['class'] = $this->classes->get(true);
        echo \FormFacade::open($attributes);
    }

    /**
     * prints form's closing
     *
     * @see    https://laravelcollective.com/docs/5.4/html
     * @return void
     */
    public function renderEnd()
    {
        echo \FormFacade::close();
    }

    /**
     * Get the view data
     * 
     * @return array
     */
    public function getViewData(): array
    {
        return ['form' => $this];
    }

}