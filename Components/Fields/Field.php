<?php
namespace Modules\Forms\Components\Fields;

use FormFacade;
use Illuminate\Database\Eloquent\Builder;
use Modules\Core\Entities\BaseModel;

abstract class Field
{
	protected $type;
	protected $name;

	public function __construct(string $name, array $options = [])
	{
		$this->name = $name;
		$this->type = strtolower(classname($this));
		$options['label'] = $options['label'] ?? $name;
		$options['attributes'] = $options['attributes'] ?? [];
		$options['attributes']['class'] = $options['attributes']['class'] ?? config('forms.input-classes.'.$this->type) ?? '';
		$options['default'] = $options['default'] ?? null;
		$options['view'] = $options['view'] ?? 'forms::fields.'.$this->type;

		$this->options = $options;
	}

	public function label()
	{
		return FormFacade::label($this->name, $this->options['label']);
	}

	public function getName()
	{
		return $this->name;
	}

	public function getType()
	{
		return $this->type;
	}

	public function render()
	{	
		$vars = ['name' => $this->name, 'type' => $this->options['type']];
		if($this->label) $vars['label'] = $this->label();
		$vars['input'] = $this->renderInput();
		echo view($this->view, $vars)->render();
	}

	public static function setModelValue(BaseModel $model, string $field, $value)
	{
		$model->$field = $value;
	}

	public function getOptions()
	{
		return $this->options;
	}

	public static function queryFilterApi(Builder $query, string $name, $value)
	{
		$query->where($name, '=', $value);
	}

	abstract public function renderInput();
}