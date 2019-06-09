<?php
namespace Pingu\Forms\Renderers;

use FormFacade;
use Pingu\Forms\Contracts\FieldContract;

abstract class FieldRenderer
{
	protected $type, $field;
	
	public function __construct(FieldContract $field)
	{	
		$this->field = $field;
		$this->type = strtolower(class_basename($this));
	}

	/**
	 * @inheritDoc
	 */
	public function getType()
	{
		return $this->type;
	}
}