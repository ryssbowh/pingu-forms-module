<?php
namespace Pingu\Forms\Support;

use Pingu\Core\Contracts\Renderable;
use Pingu\Core\Contracts\RenderableWithSuggestions;
use Pingu\Core\Traits\hasViewSuggestions;
use Pingu\Forms\Contracts\FormElementContract;
use Pingu\Forms\Support\ClassBag;

abstract class FieldRenderer implements RenderableWithSuggestions
{
	use hasViewSuggestions;

	protected $type, $field;
	public $name;
	public $attributes;
	
	public function __construct(FormElementContract $field, $attributes = [])
	{	
		$this->field = $field;
		$this->type = $this::getType();
		$this->name = $field->getName();
		$this->attributes = collect($attributes);
		$this->attributes->put('class',$this->getClasses($attributes['class'] ?? ''));
		if($field->required) $this->attributes->put('required', true);
		$this->buildViewSuggestions();
	}

	public function getValue()
	{
		return $this->field->getValue();
	}

	protected function buildViewSuggestions()
	{
		$this->setViewSuggestions([
			'forms.renderers.field-'.$this->field::getType().'_renderer-'.$this::getType(),
			'forms.renderers.renderer-'.$this::getType(),
			'forms::renderers.'.$this::getType()
		]);
	}

	/**
	 * @inheritDoc
	 */
	public static function getType()
	{
		return strtolower(class_basename(get_called_class()));
	}

	protected function getClasses(string $default)
    {
    	return $default.theme_config('forms.renderer-classes.'.$this::getType());
    }

    public function render()
    {
    	echo view()->first($this->getViewSuggestions(),['renderer' => $this, 'field' => $this->field])->render();
    }
}