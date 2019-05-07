<?php
namespace Pingu\Forms\Renderers;

use Pingu\Forms\Renderers\FieldRenderer;

abstract class InputFieldRenderer extends FieldRenderer
{
	/**
	 * Renders this field
	 * @return string
	 */
	public function render(){
		$this->options['input'] = $this->renderInput();
		echo view($this->view, $this->options)->render();
	}

	abstract public function renderInput();
}