<?php 
namespace Pingu\Forms\Contracts\Elements;

use Pingu\Core\Contracts\RenderableWithSuggestions;

interface HasValueContract extends FormElementContract
{
	public function getValue();
}