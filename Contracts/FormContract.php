<?php 
namespace Pingu\Forms\Contracts;

use Illuminate\Contracts\Support\Renderable;
use Pingu\Forms\Contracts\HasFieldsContract;
use Pingu\Forms\Contracts\HasGroupsContract;

interface FormContract extends HasFieldsContract
{
	public function name();

	public function url();

	public function attributes();

	public function actions();

	public function fields();

	public function groups();

	public function method();
}