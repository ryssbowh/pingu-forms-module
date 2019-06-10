<?php 
namespace Pingu\Forms\Contracts;

interface HasItemsContract extends HasValueContract
{
	public function getItems();
}