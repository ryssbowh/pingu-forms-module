<?php 
namespace Pingu\Forms\Contracts\Elements;

use Pingu\Core\Contracts\RenderableWithSuggestions;

interface FormElementContract extends RenderableWithSuggestions
{
	public function getName();

	public static function getType();
}