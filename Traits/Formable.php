<?php 
namespace Modules\Forms\Traits;

use Collective\Html\Eloquent\FormAccessible;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\Request;
use Modules\Forms\Components\Fields\{Text, Model};
use Modules\Forms\Events\FormMakingValidator;
use Modules\Forms\Exceptions\UnfillableFieldException;
use Validator;

trait Formable {

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
     * Makes a validator for this model
     * @param  Request $request
     * @return Validator
     */
    public function makeValidator(Request $request)
    {
    	$rules = $this->validationRules();
		$messages = $this->validationMessages();
		$validator = Validator::make($request->all(), $rules, $messages, ['request' => $request]);
		event(new FormMakingValidator($validator, $this));
		return $validator;
    }

    public function saveRelationships(array $values)
    {
        $fields = $this::fieldDefinitions();
        $return = true;
        foreach($values as $name => $value){
            if(!in_array($name, $this->fillable)) continue;

            if(method_exists($this, $name)){
                $relation = $this->$name();
                if(get_class($relation) == BelongsToMany::class){
                    $return = ($return and $fields[$name]['type']::saveRelationships($this, $name, $value));
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

}