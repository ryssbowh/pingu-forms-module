<?php 
namespace Pingu\Forms\Contracts;

interface HasModelField extends HasItemsField
{
	/**
	 * Returns the model for that field
	 * 
	 * @return string
	 */
	public function getModel();
}