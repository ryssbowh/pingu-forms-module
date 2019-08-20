<?php
namespace Pingu\Forms\Support\Fields;

use Pingu\Forms\Contracts\HasModelField;
use Pingu\Forms\Support\Form;
use Pingu\Forms\Support\ItemList;
use Pingu\Forms\Support\Types\Model;
use Pingu\Forms\Traits\HasModelItems;

class ModelSelect extends Select implements HasModelField
{
	use HasModelItems;

	protected $required = ['model', 'textField'];

	public function __construct(string $name, array $options = [], array $attributes = [])
	{
		$options['allowNoValue'] = $options['allowNoValue'] ?? true;
		$options['noValueLabel'] = $options['noValueLabel'] ?? theme_config('forms.noValueLabel');
		$options['separator'] = $options['separator'] ?? ' - ';
		$options['textField'] = is_array($options['textField']) ? $options['textField'] : [$options['textField']];
		$options['items'] = $options['items'] ?? $options['model']::all();
		parent::__construct($name, $options, $attributes);
	}

	/**
	 * @inheritDoc
	 */
	public function getModel()
	{
		return $this->option('model');
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
	public function buildItems($models)
	{
        $values = [];
        if($this->option('allowNoValue')){
        	$values[''] = $this->option('noValueLabel');
        }
        foreach($models as $model){
            $values[''.$model->id] = implode($this->option('separator'), $model->only($this->option('textField')));
        }
        return $values;
	}

	/**
	 * @inheritDoc
	 */
	public function getType()
	{
		return 'select';
	}
	
}