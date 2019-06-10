<?php
namespace Pingu\Forms\Renderers;

use Pingu\Forms\Contracts\HasItemsContract;
use Pingu\Forms\Support\FieldRenderer;

class Select extends FieldRenderer
{
	public $multiple = false;
	public $items = [];

	public function __construct(HasItemsContract $field, array $attributes)
	{
		parent::__construct($field, $attributes);
		if($this->field->multiple){
			$this->name .= '[]';
		}
		$this->buildItems();
	}

	public function getItems(){
		return $this->field->buildItems();
	}

}