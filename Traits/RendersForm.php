<?php

namespace Pingu\Forms\Traits;

use Pingu\Core\Traits\HasViewSuggestions;
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
        echo view()->first($this->getViewSuggestions(), ['form' => $this])->render();
    }

    /**
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
        $this->checkIfBuilt();
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

    public function checkIfBuilt()
    {
        if(!$this->built)
        {
            throw FormException::notBuilt($this->getName());
        }
    }

}