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

    protected $fieldDefinitionsCache = 'forms.fieldDefinitions';
    protected $builtFieldDefinitionsCache = 'forms.builtFieldDefinitions';
    protected $fieldRulesCache = 'forms.fieldRules';
    protected $fieldValidationMessagesCache = 'forms.fieldMessages';
    protected $addFormFieldsCache = 'forms.addFields';
    protected $editFormFieldsCache = 'forms.editFields';

    /**
     * List of fields for add request
     * 
     * @return array
     */
    abstract protected function formAddFields();

    /**
     * List of fields for edit request
     * 
     * @return array
     */
    abstract protected function formEditFields();

    /**
     * List of field definitions 
     * 
     * @return array
     */
    abstract protected function fieldDefinitions();

    /**
     * List of validation rules 
     * 
     * @return array
     */
    abstract protected function validationRules();

    /**
     * List of validation messages
     * 
     * @return array
     */
    abstract protected function validationMessages();

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
	 * Validation rules for this model, throws an event so
     * that other modules can change the form validation.
     * The default type for each of the field can add rules here.
     * 
     * @param  array $fields
	 * @see https://laravel.com/docs/5.7/validation
	 * @return array
	 */
    protected function getValidationRules($fields = null)
    {
        $formable = $this;
        $rules = $this->getFieldsCache($this->fieldRulesCache, function() use ($formable){
            $definitions = $formable->buildFieldDefinitions();
            $rules = $formable->validationRules();
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
            event(new ModelValidationRules($rules, $formable));
            return $rules;
        });

        if(!is_null($fields)){
            $rules = array_intersect_key($rules, array_flip($fields));
        }
        return $rules;
    }

    public function getStoreValidationRules()
    {
        return $this->getValidationRules($this->getAddFormFields());
    }

    public function getUpdateValidationRules()
    {
        return $this->getValidationRules($this->getEditFormFields());
    }

    /**
     * Validates a store request and return validated data
     * 
     * @param  Request $request
     * @param  ?array   $fields fields to be validated
     * @return array
     */
    public function validateStoreRequest(Request $request, ?array $fields = null)
    {
        if(is_null($fields)){
            $fields = $this->getAddFormFields();
        }
        return $this->validateRequestValues($request->all(), $fields);
    }

    /**
     * Validates a update request and return validated data
     * 
     * @param  Request $request
     * @param  ?array   $fields fields to be validated
     * @return array
     */
    public function validateUpdateRequest(Request $request, ?array $fields = null)
    {
        if(is_null($fields)){
            $fields = $this->getEditFormFields();
        }
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

    protected function getBundleFieldsRulesAndMessages(array $bundleFields = null)
    {
        if(!$this instanceof EntityContract){
            return [[],[]];
        }
        if(is_null($bundleFields)){
            $bundleFields = $this->bundle()->entityBundleFields();
        }
        else{
            $bundleFields = array_map(function($name){
                return $this->bundle()->getEntityBundleField(strtolower(substr($name, 6)));
            }, $bundleFields);
        }
        $rules = $messages = [];
        foreach($bundleFields as $field){
            $rules[$field] = $field->bundleFieldValidationRule($field);
            $messages[$field] = $field->bundleFieldValidationMessages($field);
        }
        return [$rules, $messages];
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
     * @param  array             $values
     * @param  array             $fields
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
        $fields = $this::buildFieldDefinitions();
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
        $definitions = $this->buildFieldDefinitions();
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

    public function buildFieldClass(string $name)
    {
        $definition = $this->getFieldDefinitions($name);
        if(is_null($definition)){
            throw FormFieldException::notDefinedInModel($name, get_class($this));
        }
        return Field::buildFieldClass($name, $definition);
    }

}