<?php 
namespace Pingu\Forms\Traits;

use Collective\Html\Eloquent\FormAccessible;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\Request;
use Pingu\Forms\Components\Fields\{Text, Model};
use Pingu\Forms\Events\FormMakingValidator;
use Pingu\Forms\Exceptions\ModelNotSaved;
use Pingu\Forms\Exceptions\ModelRelationsNotSaved;
use Validator;

trait FormableModel {

    use FormAccessible;

    /**
     * List of fields to be edited by default when adding a model through a form
     * @return array
     */
    public function addFormFields()
    {
        return $this->fillable;
    }

    /**
     * List of fields to be edited by default when editing this model through a form
     * @return array
     */
    public function editFormFields()
    {
        return $this->fillable;
    }

	/**
	 * Return field definitions for that model
	 * @return array
	 */
	public static function fieldDefinitions()
	{
		return [];
	}

	/**
	 * Validation rules for this model
	 * @see https://laravel.com/docs/5.7/validation
	 * @return array
	 */
    public function validationRules()
    {
        return [];
    }

    /**
     * Validation messages for this model
     * @see https://laravel.com/docs/5.7/validation
     * @return array
     */
    public function validationMessages()
    {
        return [];
    }

    /**
     * Validates a request and return validated data
     * @param  Request $request
     * @param  array   $fields
     * @return array
     */
    public function validateForm(Request $request, array $fields)
    {
        $validator = $this->makeValidator($request, $fields);
        $validator->validate();
        return $validator->validated();
    }

    /**
     * Makes a validator for this model, 
     * @param  Request $request
     * @param  array $fields
     * @return Validator
     */
    public function makeValidator(Request $request, array $fields)
    {
    	$rules = array_intersect_key($this->validationRules(), array_flip($fields));
		$messages = $this->validationMessages();
		$validator = Validator::make($request->all(), $rules, $messages, ['request' => $request]);
		event(new FormMakingValidator($validator, $this));
		return $validator;
    }

    /**
     * Saves the relationships for a model
     * must be called after the model is saved, so we have and id.
     * @param  array  $values [description]
     * @return [type]         [description]
     */
    public function saveRelationships(array $values)
    {
        if(!$this->id){
            throw new ModelNotSaved('Can\'t save '.$this->friendlyName().'\'s relationships : '.$this->friendlyName().' is not saved');
        }
        $fields = $this::fieldDefinitions();
        $return = false;
        foreach($values as $name => $value){
            if(!in_array($name, $this->fillable)) continue;

            if(method_exists($this, $name)){
                $relation = $this->$name();
                if(get_class($relation) == BelongsToMany::class){
                    $res = $fields[$name]['type']::saveRelationships($this, $name, $value);
                    $return = ($return or $res);
                }
            }
        }
        
        return $return;
    }

    /**
     * Populates this with values coming from a form submit
     * @param  array $values
     */
    public function formFill(array $values){
        $fields = $this::fieldDefinitions();
        foreach($values as $name => $value){
            if(in_array($name, $this->fillable)){
                $fields[$name]['type']::setModelValue($this, $name, $value);   
            }
        }
        return $this;
    }

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