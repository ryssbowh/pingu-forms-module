<?php

namespace Pingu\Forms\Support;

use Pingu\Core\Traits\HasViewSuggestions;
use Pingu\Forms\Contracts\FormElementContract;

class Macro implements FormElementContract
{
	use HasViewSuggestions;

	protected $name;
	protected $form;
	// public $required;

	public function __construct(string $name, array $options = [], ?Form $form = null)
	{
		$this->name = $name;
		$this->form = $form;
		$this->rendererAttributes = $options['attributes'] ?? [];
		unset($options['attributes']);
		$this->renderer = $options['renderer'];
		unset($options['renderer']);
		foreach($options as $option => $value){
			$this->$option = $value;
		}
	}

	public function getName()
	{
		return $this->name;
	}

	public static function getType()
	{
		return strtolower(class_basename(get_called_class()));
	}

	/**
	 * @inheritDoc
	 */
	public function renderAsString()
	{
		$renderer = new $this->renderer($this, $this->rendererAttributes);
		return $renderer->render();
	}

	public function render()
	{
		echo $this->renderAsString();
	}
	
}