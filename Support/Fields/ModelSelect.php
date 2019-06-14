<?php
namespace Pingu\Forms\Support\Fields;

use Pingu\Forms\Contracts\HasModelField;
use Pingu\Forms\Support\Form;
use Pingu\Forms\Support\Types\Model;

class ModelSelect extends ModelCheckboxes
{
	public function __construct(string $name, array $options = [], array $attributes = [])
	{
		$options['allowNoValue'] = $options['allowNoValue'] ?? true;
		$options['noValueLabel'] = $options['noValueLabel'] ?? theme_config('forms.noValueLabel');
		parent::__construct($name, $options, $attributes);
	}

	/**
	 * @inheritDoc
	 */
	public static function getDefaultType()
	{
		return Model::class;
	}

	/**
	 * @inheritDoc
	 */
	public function isMultiple()
	{
		return $this->attribute('multiple') ?? false;
	}

	/**
	 * @inheritDoc
	 */
	public function getItems()
	{
		$items = array_reverse(parent::getItems(), true);
        if($this->option('allowNoValue')){
        	$items[''] = $this->option('noValueLabel');
        	$items = array_reverse($items, true);
        }
        return $items;
	}

	/**
	 * @inheritDoc
	 */
	public function getType()
	{
		return 'select';
	}
	
}