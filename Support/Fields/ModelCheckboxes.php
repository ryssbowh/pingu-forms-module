<?php
namespace Pingu\Forms\Support\Fields;

use Pingu\Forms\Contracts\HasModelField;
use Pingu\Forms\Exceptions\FormFieldException;
use Pingu\Forms\Support\Fields\Checkboxes;
use Pingu\Forms\Support\ItemList;
use Pingu\Forms\Support\Types\ManyModel;
use Pingu\Forms\Traits\HasModelItems;

class ModelCheckboxes extends Checkboxes implements HasModelField
{
	use HasModelItems;

	protected $required = ['model', 'textField'];

	public function __construct(string $name, array $options = [], array $attributes = [])
	{
		$options['separator'] = $options['separator'] ?? ' - ';
		$options['textField'] = is_array($options['textField']) ? $options['textField'] : [$options['textField']];
		$options['items'] = $options['items'] ?? $options['model']::all();
		parent::__construct($name, $options, $attributes);
	}

	/**
	 * @inheritDoc
	 */
	public static function getDefaultType()
	{
		return ManyModel::class;
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
	public function buildItems($models)
	{
        $values = [];
        foreach($models as $model){
            $values[''.$model->id] = implode($this->option('separator'), $model->only($this->option('textField')));
        }
        return new ItemList($values, $this->getName());
	}

	/**
	 * @inheritDoc
	 */
	public function getType()
	{
		return 'checkboxes';
	}
	
}