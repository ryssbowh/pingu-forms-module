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
            $typeRules = trim($field->addValidationRules(), '|');
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
     * @param  array   $fields
     * @return array
     */
    public function validateForm(array $values, array $fields, bool $editing)
    {
        $validator = $this->makeValidator($values, $fields, $editing);
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
    public function makeValidator(array $values, array $fields, bool $editing)
    {   
        $rules = array_intersect_key($this->getValidationRules(), array_flip($fields));
        if($editing){
            //If we're editing we only validate the values that are present :
    	   $rules = array_intersect_key($rules, $values);
        }
		$messages = $this->getValidationMessages();
		$validator = Validator::make($values, $rules, $messages);
        $this->modifyValidator($validator, $values, $fields);
		event(new ModelValidator($validator, $this));
		return $validator;
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
        $return = false;
        foreach($values as $name => $value){
            if(!in_array($name, $this->fillable)) continue;

            $type = $fields[$name]->option('type');
            if(method_exists($this, $name)){
                $relation = $this->$name();
                if($relation instanceof Relation){
                    $res = $type->saveRelationships($this, $name, $value);
                    $return = ($return or $res);
                }
            }
        }
        
        return $return;
    }

    /**
     * Destroys relationships for this model
     * 
     * @return bool
     */
    public function destroyRelationships()
    {
        $fields = $this->getFieldDefinitions();
        $return = true;
        foreach($fields as $name => $data){
            $type = $this->getFieldType($name);
            if(method_exists($this, $name)){
                $res = $type::destroyRelationships($this, $name);
                $return = ($return and $res);
            }
        }
        return $return;
    }

    /**
     * Populates this with values coming from a form submit.
     * If the type isn't defined for a field, the default will be used (as defined in the field)
     * 
     * @param  array $values
     * @return  FormableModel
     */
    public function formFill(array $values){
        $definitions = $this->getFieldDefinitions();
        foreach($this->getFillableFields($values) as $name => $value){
            if($this->isFillable($name)){
                $type = $definitions[$name]->option('type');
                $type->setModelValue($this, $name, $value);   
            }
        }
        return $this;
    }

    /**
     * Filters an array of [field => value] to remove fields starting with _
     * 
     * @param  array  $fields
     * @return array
     */
    public function getFillableFields(array $fields){
        return array_filter($fields, function($field){
            return substr($field, 0, 1) != '_';
        }, ARRAY_FILTER_USE_KEY);
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

    /**
     * Helper to build a field class from a field name
     * 
     * @param  string $name
     * @return Field
     */
    public function buildFieldClass(string $name)
    {
        $fields = $this->fieldDefinitions();
        if(!isset($fields[$name])){
            throw FormFieldException::notDefinedInModel($name, get_class($this));
        }
        
        return Field::buildFieldClass($name, $fields[$name]);
    }

    public function uploadFormFile(UploadedFile $file, string $fieldName)
    {
        $field = $this->getFieldDefinitions()[$fieldName];
        if(!$field instanceof UploadFileContract){
            throw MediaFieldException::fieldCantUpload($fieldName, $field);
        }
        return $field->uploadFile($file);
    }

}