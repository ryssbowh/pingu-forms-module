<?php 
namespace Pingu\Forms\Traits\Models;

use Collective\Html\Eloquent\FormAccessible;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator as ValidatorContract;
use Pingu\Forms\Components\Fields\{Text, Model};
use Pingu\Forms\Events\AddFormFields;
use Pingu\Forms\Events\EditFormFields;
use Pingu\Forms\Events\ModelFieldDefinitions;
use Pingu\Forms\Events\ModelValidationMessages;
use Pingu\Forms\Events\ModelValidationRules;
use Pingu\Forms\Events\ModelValidator;
use Pingu\Forms\Exceptions\FieldNotDefined;
use Pingu\Forms\Exceptions\ModelNotSaved;
use Pingu\Forms\Exceptions\ModelRelationsNotSaved;
use Validator;

trait Formable {

    use FormAccessible;

    /**
     * List of fields to be edited when adding a model through a form
     * @return array
     */
    public function getAddFormFields()
    {
        $fields = $this->formAddFields();
        event(new AddFormFields($fields, $this));
        return $fields;
    }

    /**
     * List of fields to be edited when editing this model through a form
     * @return array
     */
    public function getEditFormFields()
    {
        $fields = $this->formEditFields();
        event(new EditFormFields($fields, $this));
        return $fields;
    }

	/**
	 * Return field definitions for that model
	 * @return array
	 */
	public function getFieldDefinitions($fields = null)
	{
        $definitions = $this->fieldDefinitions();
        event(new ModelFieldDefinitions($definitions, $this));
        if(!is_null($fields)){
            $definitions = array_intersect_key($definitions, array_flip($fields));
        }
		return $definitions;
	}

	/**
	 * Validation rules for this model
     * @param  array $fields
	 * @see https://laravel.com/docs/5.7/validation
	 * @return array
	 */
    public function getValidationRules($fields = null)
    {
        $rules = $this->validationRules();
        event(new ModelValidationRules($rules, $this));
        if(!is_null($fields)){
            $rules = array_intersect_key($rules, array_flip($fields));
        }
        // return $this->replaceRulesTokens($rules);
        return $rules;
    }

    /**
     * Validation messages for this model
     * @see https://laravel.com/docs/5.7/validation
     * @return array
     */
    public function getValidationMessages()
    {
        $messages = $this->validationMessages();
        event(new ModelValidationMessages($messages, $this));
        return $messages;
    }

    /**
     * Replace tokens within rules with fields from the object
     * example rule : 'required|email|unique:users,email,{id}'
     * @param  array  $rules
     * @return array
     */
    public function replaceRulesTokens(array $rules)
    {
        foreach($rules as $key => $rule){
            preg_match('/^.*\{([a-zA-Z]+)\}.*$/', $rule, $matches);
            if($matches){
                foreach($matches as $match){
                    $rule = str_replace('{'.$match.'}', $this->$match, $rule);
                }
                $rules[$key] = $rule;
            }
        }
        return $rules;
    }

    /**
     * Validates a request and return validated data
     * @param  Request $request
     * @param  array   $fields
     * @return array
     */
    public function validateForm(array $values, array $fields)
    {
        $validator = $this->makeValidator($values, $fields);
        $validator->validate();
        return $validator->validated();
    }

    /**
     * Makes a validator for this model, 
     * @param  Request $request
     * @param  array $fields
     * @return Validator
     */
    public function makeValidator(array $values, array $fields)
    {   
    	$rules = array_intersect_key($this->getValidationRules(), array_flip($fields));
		$messages = $this->getValidationMessages();
		$validator = Validator::make($values, $rules, $messages);
        $this->modifyValidator($validator, $values, $fields);
		event(new ModelValidator($validator, $this));
		return $validator;
    }

    /**
     * Hook to add rules to the validator
     * @param  ValidatorContract $validator
     */
    protected function modifyValidator(ValidatorContract $validator, array $values, array $fields){}

    /**
     * Saves the relationships for a model
     * must be called after the model is saved, so we have and id.
     * Return a bool wether of not relationships were changed.
     * @param  array  $values
     * @return bool
     */
    public function saveRelationships(array $values)
    {
        if(!$this->id){
            throw new ModelNotSaved('Can\'t save '.$this->friendlyName().'\'s relationships : '.$this->friendlyName().' is not saved');
        }
        $fields = $this::getFieldDefinitions();
        $return = false;
        foreach($values as $name => $value){
            if(!in_array($name, $this->fillable)) continue;

            if(method_exists($this, $name)){
                $relation = $this->$name();
                if($relation instanceof Relation){
                    $res = $fields[$name]['type']::saveRelationships($this, $name, $value);
                    $return = ($return or $res);
                }
            }
        }
        
        return $return;
    }

    /**
     * Destroys relationships for this model
     * @return bool
     */
    public function destroyRelationships()
    {
        $fields = $this->getFieldDefinitions();
        $return = true;
        foreach($fields as $name => $data){
            if(method_exists($this, $name)){
                $res = $data['type']::destroyRelationships($this, $name);
                $return = ($return and $res);
            }
        }
        return $return;
    }

    /**
     * Populates this with values coming from a form submit
     * @param  array $values
     * @return  FormableModel
     */
    public function formFill(array $values){
        $fields = $this::getFieldDefinitions();
        foreach($this->getFillableFields($values) as $name => $value){
            if(!isset($fields[$name])){
                throw new FieldNotDefined('field '.$name.' is not defined in '.get_class($this));
            }
            if($this->isFillable($name)){
                $fields[$name]['type']::setModelValue($this, $name, $value);   
            }
        }
        return $this;
    }

    /**
     * Filters an array of [field => value] to remove fields starting with _
     * @param  array  $fields
     * @return array
     */
    public function getFillableFields(array $fields){
        return array_filter($fields, function($field){
            return substr($field, 0, 1) != '_';
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * Saves a model and its relationships with values coming from a form.
     * @param  array  $validated
     * @return bool
     */
    public function saveWithRelations(array $validated)
    {
        $this->formFill($validated);
        if(!$this->save()){
            throw new ModelNotSaved($this::friendlyName().' could not be saved');
        }
        try{
            $changesRelation = $this->saveRelationships($validated);
        }
        catch(\Exception $e){
            throw new ModelRelationsNotSaved($this::friendlyName().' relations could not be saved');
        }
        
        return ($this->getChanges() or $changesRelation);
    }

}