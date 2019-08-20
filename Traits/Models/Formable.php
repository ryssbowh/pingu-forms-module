<?php 
namespace Pingu\Forms\Traits\Models;

use Collective\Html\Eloquent\FormAccessible;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Validator as ValidatorContract;
use Pingu\Forms\Components\Fields\{Text, Model};
use Pingu\Forms\Events\AddFormFields;
use Pingu\Forms\Events\EditFormFields;
use Pingu\Forms\Events\ModelFieldDefinitions;
use Pingu\Forms\Events\ModelValidationMessages;
use Pingu\Forms\Events\ModelValidationRules;
use Pingu\Forms\Events\ModelValidator;
use Pingu\Forms\Exceptions\FieldNotDefined;
use Pingu\Forms\Exceptions\FormFieldException;
use Pingu\Forms\Exceptions\ModelNotSaved;
use Pingu\Forms\Exceptions\ModelRelationsNotSaved;
use Pingu\Forms\Support\Field;
use Pingu\Forms\Support\Type;
use Pingu\Media\Contracts\UploadFileContract;
use Validator;

trait Formable {

    use FormAccessible;

    protected $fieldDefinitionsCache;
    protected $fieldRulesCache;
    protected $fieldValidationMessagesCache;

    /**
     * List of fields for add request
     * 
     * @return array
     */
    abstract function formAddFields();

    /**
     * List of fields for edit request
     * 
     * @return array
     */
    abstract function formEditFields();

    /**
     * List of field definitions 
     * 
     * @return array
     */
    abstract function fieldDefinitions();

    /**
     * List of validation rules 
     * 
     * @return array
     */
    abstract function validationRules();

    /**
     * List of validation messages
     * 
     * @return array
     */
    abstract function validationMessages();

    /**
     * This define an identifier for a form that handles this model.
     * It's used to generate the form name.
     * 
     * @return string
     */
    public static function formIdentifier()
    {
        return kebab_case(static::friendlyName());
    }

    /**
     * List of fields to be edited when adding a model through a form
     * 
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
     * 
     * @return array
     */
    public function getEditFormFields()
    {
        $fields = $this->formEditFields();
        event(new EditFormFields($fields, $this));
        return $fields;
    }

	/**
	 * Return field definitions for that model and throws an event
     * so that other modules can modify the form definition.
     * Definitions will be kept in cache so the event is thrown only once per request.
     * 
     * @throws FormFieldException
	 * @return array
	 */
	public function getFieldDefinitions($fields = null)
	{
        if(!is_null($this->fieldDefinitionsCache)){
            $definitions = $this->fieldDefinitionsCache;
        }
        else{
            $definitions = $this->buildFieldDefinitions();
        }
        if(!is_null($fields)){
            $definitions = array_intersect_key($definitions, array_flip($fields));
        }
        return $definitions;
    }

    /**
     * Builds this model fields definitions and store them in a variable as cache
     * 
     * @return array
     */
    protected function buildFieldDefinitions()
    {
        $definitions = $this->fieldDefinitions();
        foreach($definitions as $name => $definition){
            if(!isset($definition['field'])){
                throw FormFieldException::missingDefinition($name, 'field');
            }
            $definitions[$name] = $definition['field']::buildFieldClass($name, $definition);
        }
        event(new ModelFieldDefinitions($definitions, $this));
        $this->fieldDefinitionsCache = $definitions;
        
		return $definitions;
	}

	/**
	 * Validation rules for this model, throws an event so
     * that other modules can change the form validation.
     * The default type for each of the field can add rules here.
     * 
     * @param  array $fields
	 * @see https://laravel.com/docs/5.7/validation
	 * @return array
	 */
    public function getValidationRules($fields = null)
    {
        if(!is_null($this->fieldRulesCache)){
            $rules = $this->fieldRulesCache;
        }
        else{
            $rules = $this->buildValidationRules();
        }
        if(!is_null($fields)){
            $rules = array_intersect_key($rules, array_flip($fields));
        }
        return $rules;
    }

    /**
     * Builds the validation rules and store them in a variable as cache
     * 
     * @return array
     */
    protected function buildValidationRules()
    {
        $definitions = $this->getFieldDefinitions();
        $rules = $this->validationRules();
        foreach($rules as $fieldName => $rule){
            if(!isset($definitions[$fieldName])) continue;
            /**
             * Adding each field custom validation rules
             */
            $field = $definitions[$fieldName];
            $typeRules = trim($field->extraValidationRules(), '|');
            $rule .= '|'.$typeRules;
            $rules[$fieldName] = trim($rule, '|');
        }
        event(new ModelValidationRules($rules, $this));
        return $rules;
    }

    /**
     * Validation messages for this model
     * @see https://laravel.com/docs/5.7/validation
     * @return array
     */
    public function getValidationMessages()
    {
        if(!is_null($this->fieldValidationMessagesCache)){
            return $this->fieldValidationMessagesCache;
        }
        return $this->buildValidationMessages();
    }

    /**
     * Builds the validation messages and store them in a variable as a cache
     * 
     * @return array
     */
    protected function buildValidationMessages()
    {
        $messages = $this->validationMessages();
        event(new ModelValidationMessages($messages, $this));
        $this->fieldValidationMessagesCache = $messages;
        return $messages;
    }

    /**
     * Validates a request and return validated data
     * 
     * @param  Request $request
     * @param  array   $fields fields to be validated
     * @return array
     */
    public function validateRequest(Request $request, array $fields)
    {
        return $this->validateRequestValues($request->all(), $fields);
    }

    /**
     * Validates values and return validated data
     * 
     * @param  array $values
     * @param  array   $fields fields to be validated
     * @return array
     */
    public function validateRequestValues(array $values, array $fields)
    {
        $validator = $this->makeValidator($values, $fields);
        $validator->validate();
        return $validator->validated();
    }

    /**
     * Makes a validator for this model.
     * 
     * @param  array $values
     * @param  array $fields
     * @return Validator
     */
    public function makeValidator(array $values, array $fields)
    {   
        //1 - remove non fillable fields
        $values = $this->removeNonFormableValues($values);
        //2 - $fields contains the fields to validate (add or edit fields), but
        //maybe fields have been manually added to the form, so need to merge the two
        $fields = array_unique(array_merge($fields, array_keys($values)));
        //3 - make sure a validation rule exist for all present fields
        $this->ensureRulesExist($fields);
        //4 - Select rules for the fields
        $rules = array_intersect_key($this->getValidationRules(), array_flip($fields));
		$messages = $this->getValidationMessages();
		$validator = Validator::make($values, $rules, $messages);
        $this->modifyValidator($validator, $values, $fields);
		event(new ModelValidator($validator, $this));
		return $validator;
    }

    /**
     * Ensure that a rule is defined for every field
     * 
     * @param array  $fields
     * @throws FormFieldException
     */
    protected function ensureRulesExist(array $fields)
    {
        $rules = $this->getValidationRules();
        foreach($fields as $field){
            if(!isset($rules[$field])){
                throw FormFieldException::missingRule($field, $this);
            }
        }
    }

    /**
     * Remove all values which start with underscore
     * 
     * @param  array  $values
     * @return array
     */
    protected function removeNonFormableValues(array $values)
    {
        return array_filter($values, function($key){
            return substr($key, 0, 1) != '_';
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * Hook to add rules to the validator
     * 
     * @param  ValidatorContract $validator
     */
    protected function modifyValidator(ValidatorContract $validator, array $values, array $fields){}

    /**
     * Saves the relationships for a model
     * must be called after the model is saved, so we have an id.
     * Return a bool wether of not relationships were changed.
     * 
     * @param  array  $values
     * @return bool
     */
    public function saveRelationships(array $values)
    {
        if(!$this->id){
            throw new ModelNotSaved('Can\'t save '.$this->friendlyName().'\'s relationships : '.$this->friendlyName().' is not saved');
        }
        $fields = $this::getFieldDefinitions();
        $changes = false;
        foreach($values as $name => $value){
            if(!in_array($name, $this->fillable)) continue;

            $type = $fields[$name]->option('type');
            if(method_exists($this, $name)){
                $relation = $this->$name();
                if($relation instanceof Relation){
                    $change = $type->saveRelationships($this, $value);
                    $changes = ($changes or $change);
                }
            }
        }
        return $changes;
    }

    /**
     * Destroys relationships for this model
     * 
     * @return bool
     */
    // public function destroyRelationships()
    // {
    //     $fields = $this->getFieldDefinitions();
    //     $changes = true;
    //     foreach($fields as $name => $data){
    //         $type = $fields[$name]->option('type');
    //         if(method_exists($this, $name)){
    //             $change = $type::destroyRelationships($this);
    //             $changes = ($changes and $change);
    //         }
    //     }
    //     return $changes;
    // }

    /**
     * Populates this with values coming from a form submit.
     * If the type isn't defined for a field, the default will be used (as defined in the field)
     * 
     * @param  array $values
     * @return  FormableModel
     */
    public function formFill(array $values){
        $definitions = $this->getFieldDefinitions();
        foreach($values as $name => $value){
            $type = $definitions[$name]->option('type');
            $type->setModelValue($this, $value);   
        }
        return $this;
    }

    /**
     * Saves a model and its relationships with validated values coming from a request.
     * 
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