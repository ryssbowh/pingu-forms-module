<?php 

namespace Pingu\Forms\Traits;

use Illuminate\Database\Eloquent\Builder;
use Pingu\Core\Entities\BaseModel;
use Pingu\Forms\Support\ClassBag;
use Pingu\Forms\Support\Form;

trait Field
{
	protected $form;
	public $name;
	public $label;
	public $value = null;
	public $renderer;
	public $required = false;
	public $classes;
	public $labelClasses;
	public $innerClasses;
	protected $rendererAttributes;
	protected $viewSuggestions;

	public function __construct(string $name, array $options = [], ?Form $form = null)
	{
		$this->name = $name;
		$this->form = $form;
		$this->rendererAttributes = $options['attributes'] ?? [];
		unset($options['attributes']);

		foreach($options as $option => $value){
			$this->$option = $value;
		}
		/**
		 * If the label is not set, default to the name
		 */
		$this->label = isset($options['label']) ? ($options['label'] === false ? false : $options['label']) : ucfirst($this->name);
		$this->classes = new ClassBag($this->getClasses());
		$this->labelClasses = new ClassBag($this->getLabelClasses());
		$this->innerClasses = new ClassBag($this->getInnerClasses());
		$this->buildViewSuggestions();
	}

	protected function buildViewSuggestions()
	{
		if($this->form){
			$this->viewSuggestions[] = 'forms.fields.form-'.$this->form->getName().'_field-'.$this::getType();
		}
		$this->setViewSuggestions(array_merge($this->viewSuggestions,[
			'forms.fields.field-'.$this::getType(),
			'forms.fields.field',
			'forms::field'
		]));
	}

	protected function getClasses()
    {
        return theme_config('forms.field-classes.'.$this->getName()) ??
            theme_config('forms.field-default-classes').' field-'.$this->getName();
    }

    protected function getLabelClasses()
    {
        return theme_config('forms.label-classes.'.$this->getName()) ??
            theme_config('forms.label-default-classes').' label-'.$this->getName();
    }

    protected function getInnerClasses()
    {
    	return theme_config('forms.field-inner-classes.'.$this->getName()) ??
            theme_config('forms.field-inner-default-classes').' field-'.$this->getName().'-inner';
    }

    public static function getType()
	{
		return strtolower(class_basename(get_called_class()));
	}

	/**
	 * @inheritDoc
	 */
	public function setValue($value)
	{
		$this->value = $value;
		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function getValue()
	{
		return $this->value;
	}

	/**
	 * @inheritDoc
	 */
	public function getName()
	{
		return $this->name;
	}

	public static function setModelValue(BaseModel $model, string $field, $value, array $fieldOptions)
	{
		$model->$field = $value;
	}

	
	public static function filterQueryModifier(Builder $query, string $name, $value)
	{
		if($value){
			$query->where($name, '=', $value);
		}
	}

	public static function saveRelationships(BaseModel $model, string $name, $value)
	{
		return false;
	}

	public static function destroyRelationships(BaseModel $model, string $name)
	{
		return true;
	}
	
	public function render()
	{
		$renderer = new $this->renderer($this, $this->rendererAttributes);
		echo view()->first($this->getViewSuggestions(), ['field' => $this, 'renderer' => $renderer])->render();
	}
}