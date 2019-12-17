<?php 

namespace Pingu\Forms\Support;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Pingu\Forms\Forms\FieldOptionsForm;
use Pingu\Forms\Support\Form;

class FieldOptions implements Arrayable
{
    /**
     * Options defined by this class
     * @var array
     */
    protected $optionNames = [];

    /**
     * @var array
     */
    protected $labels = [];

    /**
     * @var array
     */
    protected $values = [];

    /**
     * @var string
     */
    protected $formFieldClass;

    /**
     * Casts for values
     * supported : bool, int, float
     * @var array
     */
    protected $casts = [];

    /**
     * Constructor
     * 
     * @param array  $values
     */
    public function __construct(array $values = [])
    {
        if ($this->formFieldClass) {
            $this->values = array_merge($this->formFieldClass::defaultOptions(), $values);
        } else {
            $this->values = $values;
        }
    }

    /**
     * Get form elements for the options
     * 
     * @return array
     */
    public function toFormElements(): array
    {
        return [];
    }

    /**
     * Validation rules for the edit options request
     * 
     * @return array
     */
    public function getValidationRules(): array
    {
        return [];
    }

    /**
     * Validation messages for the options
     * 
     * @return array
     */
    public function getValidationMessages(): array
    {
        return [];
    }

    /**
     * Set the values
     * 
     * @param array $values
     */
    public function setValues(array $values): FieldOptions
    {
        $this->values = $values;
        return $this;
    }

    /**
     * Does this class define options
     * 
     * @return boolean
     */
    public function hasOptions(): bool
    {
        return !empty($this->optionNames);
    }

    /**
     * Form field class getter
     * 
     * @return string
     */
    public function formFieldClass(): string
    {
        return $this->formFieldClass;
    }

    /**
     * Friendly description for the options
     * 
     * @return string
     */
    public function friendlyDescription(): string
    {
        $out = '';
        foreach ($this->optionNames as $name) {
            $out .= '<p>'.$this->label($name).': '.$this->friendlyValue($name).'</p>';
        }
        return $out;
    }

    /**
     * Label for an option name
     * 
     * @param string $name
     * 
     * @return string
     */
    public function label(string $name): string
    {
        return $this->labels[$name] ?? '';
    }

    /**
     * Value for an option name
     * 
     * @param string $name 
     * 
     * @return mixed
     */
    public function value(string $name)
    {
        return $this->values[$name] ?? null;
    }

    /**
     * Friendly value for an option name
     * 
     * @param string $name 
     * 
     * @return mixed
     */
    public function friendlyValue(string $name)
    {
        return $this->values[$name] ?? null;
    }

    /**
     * Get the form to edit the options
     * 
     * @param array  $action
     * 
     * @return Form
     */
    public function getEditForm(array $action): Form
    {
        return new FieldOptionsForm($action, $this);
    }

    /**
     * Validate a edit option request
     * 
     * @param Request $request
     */
    public function validate(Request $request)
    {
        $values = $request->except(['_token']);
        $rules = $this->getValidationRules();
        $messages = $this->getValidationMessages();
        $validator = \Validator::make($values, $rules, $messages);
        $validator->validate();
        $this->values = $this->castValues($validator->validated());
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'values' => $this->values,
            'description' => $this->friendlyDescription(),
            'hasOptions' => $this->hasOptions()
        ];
    }

    /**
     * Values getter
     * 
     * @return array
     */
    public function values(): array
    {
        return $this->values;
    }

    /**
     * Casts an array of values
     * 
     * @param  array  $values
     * @return array
     */
    protected function castValues(array $values): array
    {
        foreach ($values as $name => $value) {
            switch ($this->casts[$name] ?? '') {
                case 'bool':
                    $values[$name] = (bool)$value;
                    break;
                case 'int':
                    $values[$name] = (int)$value;
                    break;
                case 'float':
                    $values[$name] = (float)$value;
                    break;
                default:
                    break;
            }
        }
        return $values;
    }
}