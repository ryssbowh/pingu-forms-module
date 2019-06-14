<?php 
namespace Pingu\Forms\Contracts;

interface HasItemsField
{
	/**
	 * Gets the field items to choose from
	 * 
	 * @return array
	 */
	public function getItems();

	/**
	 * Can the user choose several items
	 * 
	 * @return boolean
	 */
	public function isMultiple();
}