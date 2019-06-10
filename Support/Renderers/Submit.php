<?php
namespace Pingu\Forms\Renderers;

use Pingu\Forms\Support\Macro;
use Pingu\Forms\Support\FieldRenderer;
use Pingu\Forms\Support\Form;

class Submit extends FieldRenderer
{
	public $label;

	public function __construct(Macro $field, array $attributes)
	{
		parent::__construct($field, $attributes);
		$this->label = $field->label;
		if(!$this->label){
			$this->label = 'Submit';
		}
	}
}