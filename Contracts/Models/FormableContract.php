<?php 
namespace Pingu\Forms\Contracts\Models;

use Illuminate\Http\Request;

interface FormableContract {

    /**
     * List of fields to be edited by default when creating this model through a form
     * 
     * @return array
     */
    public function getAddFormFields();

    /**
     * List of fields to be edited by default when editing this model through a form
     * 
     * @return array
     */
    public function getEditFormFields();

	/**
	 * Return field definitions for that model
     * 
	 * @return array
	 */
	public function getFieldDefinitions();

    /**
     * Build an array of field classes
     * 
     * @return array
     */
    public function buildFieldDefinitions();

	/**
	 * Validation rules for this model for store requests
     * 
	 * @see https://laravel.com/docs/5.7/validation
	 * @return array
	 */
    public function getStoreValidationRules();

    /**
     * Validation rules for this model for edit requests
     * 
     * @see https://laravel.com/docs/5.7/validation
     * @return array
     */
    public function getUpdateValidationRules();

    /**
     * Validation messages for this model
     * 
     * @see https://laravel.com/docs/5.7/validation
     * @return array
     */
    public function getValidationMessages();

    /**
     * Returns a form identifier
     * 
     * @return string
     */
    public static function formIdentifier();

    /**
     * Get form value.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function getFormValue(string $key);

    /**
     * Build a form field class according to field definition
     * 
     * @param  string $name
     * @return Field
     */
    public function buildFieldClass(string $name);

}