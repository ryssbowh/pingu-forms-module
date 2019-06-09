<?php

namespace Pingu\Forms\Traits;


trait RendersForm
{
    public function getViewSuggestions()
    {
        return ['forms.form-'.$this->getName(), 'forms.form', 'forms::form'];
    }

    public function renderActions()
    {
        echo view('forms::actions', ['actions' => $this->actions->toArray()])->render();
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
     * Renders the form and returns as string
     * @return string
     */
    public function renderAsString()
    {   
        $this->end();
        return view()->first($this->getViewSuggestions(), ['form' => $this])->render();
    }

    /**
     * 
     * @param array $names  
     */
    public function renderGroups(array $names = null)
    {
        $groups = $this->getGroups($names);
        foreach($groups as $name => $fields){
        	$groups[$name] = $this->getFields($fields);
        }
        echo view('forms::groups', ['form' => $this, 'groups'=>$groups])->render();
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
        echo \FormFacade::open($this->attributes->toArray());
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

}