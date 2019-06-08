<?php
namespace Pingu\Forms\Fields;

use Pingu\Forms\Renderers\Select;

class Serie extends Field
{
	public function __construct(string $name, array $options = [])
	{
		$options['renderer'] = $options['renderer'] ?? Select::class;
		$options['allowNoValue'] = $options['allowNoValue'] ?? true;
		$options['noValueLabel'] = $options['noValueLabel'] ?? 'Select';
		$options['multiple'] = $options['multiple'] ?? false;
		$options['items'] = $options['items'] ?? [];
		parent::__construct($name, $options);
	}

	public function buildItems()
	{
		$items = $this->options['items'];
        if($this->options['allowNoValue']){
        	array_unshift([0 => $this->options['noValueLabel']], $items);
        }
		return $items;
	}
}