<?php
namespace Modules\Forms\Components\Fields;

use FormFacade;
use Illuminate\Database\Eloquent\Builder;

class Text extends Field
{

	public function __construct(string $name, array $options = [])
	{
		parent::__construct($name, $options);
	}

	public function renderInput()
	{
		return FormFacade::text($this->name, $this->options['default'], $this->options['attributes']);
	}

	public static function queryFilterApi(Builder $query, string $name, $value)
	{
		$query->where($name, 'like', '%'.$value.'%');
	}
}