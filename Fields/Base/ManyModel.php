<?php
namespace Pingu\Forms\Fields\Base;

class ManyModel extends Model
{
	protected $models;

	public function __construct(string $name, array $options = [])
	{
		$this->models = collect();
		$options['multiple'] = true;
		parent::__construct($name, $options);
	}

	public function getModels()
	{
		return $this->models;
	}

	/**
	 * @inheritDoc
	 */
	public function setValue($models)
	{
		$this->models = $models;
		$default = [];
		if(!is_null($models)){
			foreach($models as $item){
				$default[] = (string)$item->id;
			}
		}
		$this->options['default'] = $default;
	}
}