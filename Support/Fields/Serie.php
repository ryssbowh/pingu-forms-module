<?php
namespace Pingu\Forms\Support\Fields;

use Pingu\Forms\Contracts\HasItemsContract;
use Pingu\Forms\Renderers\Select;
use Pingu\Forms\Support\Field;
use Pingu\Forms\Support\Form;

class Serie extends Field implements HasItemsContract
{
	public $noValueLabel;
	public $allowNoValue = true;
	public $multiple = false;

	public function __construct(string $name, array $options = [], ?Form $form = null)
	{
		$options['noValueLabel'] = $options['noValueLabel'] ?? config('forms.noValueLabel');
		parent::__construct($name, $options, $form);
	}

	/**
	 * Need to cast to string here, php is weird with arrays having both integer and string keys
	 * @param mixed $value
	 */
	public function setValue($value)
	{
		if(!is_array($value)){
			$value = [$value];
		}
		array_map(function($item){
			return (string)$item;
		}, $value);
		
		$this->value = $value;
	}

	/**
	 * Builds items, casting all keys into string, so we can compare with value securely
	 * @return array
	 */
	public function buildItems()
	{
		$values = $this->allowNoValue ? ['' => $this->noValueLabel] : [];
		foreach($this->items as $key => $item){
            $values[''.$key] = $item;
        }
        return $values;
	}
}