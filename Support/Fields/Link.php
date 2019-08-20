<?php
namespace Pingu\Forms\Support\Fields;

use Pingu\Forms\Support\NoValueField;

class Link extends NoValueField
{
	protected $required = ['label', 'url'];
}