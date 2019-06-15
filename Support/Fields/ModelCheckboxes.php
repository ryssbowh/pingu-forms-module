<?php
namespace Pingu\Forms\Support\Fields;

use Pingu\Forms\Contracts\HasModelField;
use Pingu\Forms\Exceptions\FormFieldException;
use Pingu\Forms\Support\Fields\Checkboxes;
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
		parent::__construct($name, $options, $attributes);
		$this->option('items', $this->option('items') ?? $this->option('model')::all());
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
	public function getItems()
	{
		$models = $this->option('items');
        $values = [];
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
		return 'checkboxes';
	}

	/**
	 * @inheritDoc
	 */
	public function getDefaultView()
	{
		return 'forms::fields.'.$this->getType();
	}
	
}