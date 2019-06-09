<?php
namespace Pingu\Forms\Fields\Base;

use Pingu\Forms\Renderers\Select;
use Pingu\Forms\Support\Field;

class Serie extends Field
{
	public $noValueLabel;
	public $allowNoValue = true;
	public $multiple = false;
	public $items = [];

	public function __construct(string $name, array $options = [])
	{
		$options['noValueLabel'] = $options['noValueLabel'] ?? config('forms.noValueLabel');
		parent::__construct($name, $options);
	}

	/**
	 * Builds the list of items available to choose from
	 * @return array
	 */
	public function buildItems()
	{
		$items = $this->options['items'];
        if($this->options['allowNoValue']){
        	array_unshift([0 => $this->options['noValueLabel']], $items);
        }
		return $items;
	}

	/**
	 * @inheritDoc
	 */
	public function getDefaultRenderer()
	{
		return Select::class;
	}
}