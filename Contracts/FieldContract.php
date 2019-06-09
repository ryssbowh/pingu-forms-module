<?php 
namespace Pingu\Forms\Contracts;

interface FieldContract
{
	public function getType();

	public function setValue($value);

	public function getValue();

	public function getName();

	public function getDefaultRenderer();
}