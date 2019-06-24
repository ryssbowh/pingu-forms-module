<?php 
namespace Pingu\Forms\Contracts\Models;

use Illuminate\Http\Request;

interface FormableContract {

    /**
     * List of fields to be edited by default when creating this model through a form
     * 
     * @return array
     */
    public function formAddFields();

    /**
     * List of fields to be edited by default when editing this model through a form
     * 
     * @return array
     */
    public function formEditFields();

	/**
	 * Return field definitions for that model
     * 
	 * @return array
	 */
	public function fieldDefinitions();

	/**
	 * Validation rules for this model
     * 
	 * @see https://laravel.com/docs/5.7/validation
	 * @return array
	 */
    public function validationRules();

    /**
     * Validation messages for this model
     * 
     * @see https://laravel.com/docs/5.7/validation
     * @return array
     */
    public function validationMessages();

    /**
     * Returns a form identifier
     * 
     * @return string
     */
    public static function formIdentifier();

    /**
     * Build a field class according to its definition
     * 
     * @param  string $name
     * @return Field
     */
    public function buildFieldClass(string $name);

}