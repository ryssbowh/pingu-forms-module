<?php
namespace Pingu\Forms\Renderers;

use Pingu\Forms\Contracts\FormContract;
use Pingu\Forms\Renderers\FieldRenderer;

class Select extends FieldRenderer
{
	public function __construct(FormContract $field)
	{
		parent::__construct($field);
		$this->buildItems();
	}

	public function buildItems(){
		$this->options['items'] = $this->field->buildItems();
		$this->options['rendererAttributes']['id'] = $this->options['name'];
		if($this->options['multiple']){
			$this->options['name'] .= '[]';
			$this->options['rendererAttributes']['multiple'] = true;
		}
	}	
}