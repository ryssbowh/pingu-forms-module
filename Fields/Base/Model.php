<?php
namespace Pingu\Forms\Fields\Base;

use Pingu\Forms\Exceptions\FieldMissingAttributeException;

class Model extends Serie
{
	public $separator = ' - ';
	public $model;
	public $queryCallback;
	public $textField;

	public function __construct(string $name, array $options = [])
	{
		if(!isset($options['model'])){
			throw new FieldMissingAttributeException('Field '.$name.' is missing a \'model\' option');
		}
		if(!isset($options['textField'])){
			throw new FieldMissingAttributeException('Field '.$name.' is missing a \'textField\' option');
		}

		parent::__construct($name, $options);
	}

	/**
	 * @inheritDoc
	 */
	public function buildItems()
	{
		$callback = isset($this->options['queryCallback']) ? $this->options['queryCallback'] : false;

		if($callback and method_exists($callback[0], $callback[1])){
			$models = call_user_func($callback, $this);
		}
		else{
			$models = $this->options['model']::all();
		}
        $values = [];
        if($this->options['allowNoValue']){
        	$values[''] = $this->options['noValueLabel'];
        }
        foreach($models as $model){
            $values[''.$model->id] = implode($this->options['separator'], $model->only($this->options['textField']));
        }
        return $values;
	}
}