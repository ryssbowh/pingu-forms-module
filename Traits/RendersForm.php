<?php

namespace Pingu\Forms\Traits;

use Pingu\Core\Traits\HasViewSuggestions;
use Pingu\Forms\Events\FormBuilt;
use Pingu\Forms\Exceptions\FormException;

trait RendersForm
{
    use HasViewSuggestions;

    protected function buildViewSuggestions()
    {
        $this->setViewSuggestions([
            'forms.form-'.$this->name,
            'forms.form',
            'forms::form'
        ]);
    }

	/**
     * Prints this form
     * 
     * @return string
     */
    public function render()
    {
        echo $this->renderAsString();
    }

    /**
     * Renders the fields of this forms, group by group
     * 
     * @param array $names  
     */
    public function renderFields(array $names = null)
    {
        $groups = $this->buildGroups($names);
        echo view('forms::groups', ['form' => $this, 'groups'=> $groups])->render();
    }

    /**
     * print form's opening
     *
     * @see  https://laravelcollective.com/docs/5.4/html
     * @throws FormNotBuiltException
     * @return void
     */
    public function renderStart()
    {
        \FormFacade::considerRequest();
        event(new FormBuilt($this->getName(), $this));
        $attributes = $this->attributes->toArray();
        echo \FormFacade::open($attributes);
    }

    /**
     * prints form's closing
     *
     * @see  https://laravelcollective.com/docs/5.4/html
     * @return void
     */
    public function renderEnd()
    {
        echo \FormFacade::close();
    }

    /**
     * If this form isn't built we can't render it.
     * 
     * @throws FormException
     */
    public function checkIfBuilt()
    {
        if(!$this->built)
        {
            throw FormException::notBuilt($this->getName());
        }
    }

    public function renderAsString()
    {
        return view()->first($this->getViewSuggestions(), ['form' => $this])->render();
    }

}