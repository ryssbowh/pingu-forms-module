<?php
/**
 * Form provides with helpers to build a form and print it with the help of the laravel collective Form Facade.
 * Example of use :
 * 
 * $form = new Form('test', ['route' => 'my.route'], ['submit' => ['Go!']], ['field1', 'field2']);
 * $form->end();
 *
 * In your template :
 * $form->render();
 *
 * @package Forms
 * @author  Boris Blondin
 * @version 1.0
 * @see  https://laravelcollective.com/docs/5.4/html
 */

namespace Pingu\Forms;

use FormFacade;
use Pingu\Forms\Components\Fields\Text;
use Pingu\Forms\Events\FormBuilt;
use Pingu\Forms\Exceptions\FieldMissingAttributeException;
use Pingu\Forms\Exceptions\FormNotBuiltException;

class Form
{
    protected $attributes;
    protected $name;
    protected $options;
    protected $fields = [];
    protected $isBuilt = false;
    protected $defaults = [
        'submit' => ['Submit'],
        'view' => 'forms::form',
        'groupView' => 'forms::formGroup',
        'groups' => []
    ];

    /**
     * Creates a new form. Set the default options (views, layout) and build the fields (if provided).
     * 
     * @param string      $name
     * @param array       $attributes
     * @param array       $options
     * @param array|null  $fields
     */
    public function __construct(string $name, array $attributes, ?array $options = [], ?array $fields = null )
    {   
        $this->attributes = $attributes;
        $this->name = $name;
        $this->options = array_merge($this->defaults, $options);

        if(!isset($this->options['submit'][1]['class'])){
            $this->options['submit'][1]['class'] = theme_config('forms.submit-default-classes');
        }

        if($fields) $this->addFields($fields);

        if(!isset($this->options['layout'])) $this->options['layout'] = array_keys($fields);

        $this->attributes['class'] = $this->getClasses();
    }

    public function getClasses(){
        $classes = $this->attributes['class'] ?? theme_config('forms.form-default-classes').' form-'.$this->name;
        if($themeClass = theme_config('forms.classes.'.$this->name)){
            $classes .= ' '.$themeClass;
        }
        return $classes;
    }

    /**
     * Is this group defined in this form
     * 
     * @param  string  $group
     * @return boolean
     */
    public function isGroup(string $group):bool
    {
        return isset($this->options['groups'][$group]);
    }

    /**
     * Is this field defined in this form
     * 
     * @param  string  $field
     * @return boolean
     */
    public function isField(string $field):bool
    {
        return isset($this->fields[$field]);
    }

    /**
     * @param array $layout
     */
    public function setLayout(array $layout):Form
    {
        $this->options['layout'] = $view;
        return $this;
    }

    /**
     * Add a field to the layout
     * 
     * @param string $name
     */
    public function addFieldToLayout(string $name)
    {
        $this->options['layout'][] = $name;
        return $this;
    }

    /**
     * Add a field to a group
     * 
     * @param string $group
     * @param string $name
     */
    public function addFieldToGroup(string $group, string $name)
    {
        if($this->isGroup($group)){
            $this->options['groups'][$group][] = $name;
        }
        return $this;
    }

    /**
     * @param string $view
     */
    public function setView($view):Form
    {
        $this->options['view'] = $view;
        return $this;
    }

    /**
     * Get form's name
     * 
     * @return string
     */
    public function getName():string
    {
        return $this->name;
    }

    /**
     * Get a form option
     *
     * @param  string $name
     * @return mixed
     */
    public function getOption(string $name)
    {
        return $this->options[$name] ?? null;
    }

    /**
     * Is this form finished building ?
     * 
     * @return boolean
     */
    public function isBuilt():bool
    {
        return $this->isBuilt;
    }

    /**
     * Does this form has an attribute named $name
     * 
     * @param  string  $name
     * @return boolean
     */
    public function hasAttribute(string $name):bool
    {
        return (isset($this->attributes[$name]));
    }

    /**
     * Add an attribute to this form
     * 
     * @param string $name
     * @param mixed $value
     * @return  Form
     */
    public function addAttribute(string $name, $value):Form
    {
        if($this->hasAttribute($name)) $this->attributes[$name] = $value;
        return $this;
    }

    /**
     * Remove an attribute from this form
     * 
     * @param  string $name
     * @return Form
     */
    public function removeAttribute(string $name):Form
    {
        if($this->hasAttribute($name)) unset($this->attributes[$name]);
        return $this;
    }

    /**
     * Returns a list of field names
     * 
     * @return array
     */
    public function getFieldsList()
    {
        return array_keys($this->fields);
    }

    /**
     * Returns a field by its name
     * 
     * @return Field
     */
    public function getField(string $name)
    {
        return $this->fields[$name] ?? null;
    }

    /**
     * Remove a field from this form
     * 
     * @param  string $name
     * @return Form
     */
    public function removeField(string $name): Form
    {
        if($this->hasField($name)) unset($this->fields[$name]);
        return $this;
    }

    /**
     * Does the field $name has an attribute called $attributeName
     * 
     * @param  string $name          
     * @param  string $attributeName
     * @return boolean
     */
    public function fieldHasOption(string $name, string $attributeName):bool
    {
        return ($this->hasField($name) and $this->fields[$name]->hasOption($attributeName));
    }

    /**
     * Adds an attribute to the field $name
     * 
     * @param string $name 
     * @param string $attributeName
     * @param mixed $value
     * @return  Form
     */
    public function setFieldOption(string $name, string $attributeName, $value):Form
    {
        $this->fields[$name]->setOption($attributeName, $value);
        return $this;
    }

    /**
     * Removes a an attribute for the field $name
     * 
     * @param  string $name
     * @param  string $attributeName
     * @return Form
     */
    public function removeFieldOption(string $name, string $attributeName):Form
    {
        $this->fields[$name]->removeOption($attributeName);
        return $this;
    }

    /**
     * Sets a field's value
     * @param string $name
     * @param $value
     * @return  Form
     */
    public function setFieldValue(string $name, $value)
    {
        if($field = $this->getField($name)){
            $field->setDefault($value);
        }
        return $this;
    }

    /**
     * Marks this form as built, populates the fields if applicable and sends an event
     *
     * @return Form
     */
    public function end():Form
    {   
        FormFacade::considerRequest();
        event(new FormBuilt($this->name, $this));
        $this->isBuilt = true;
        return $this;
    }

    /**
     * Add a field to this form, default to Text if type not present
     * 
     * @param string $name
     * @param array  $options
     * @return  Form
     */
    public function addField(string $name, array $options):Form
    {   
        if(!isset($options['type'])) $options['type'] = Text::class;
        $field = new $options['type']($name, $options);
        $field->setDefault($options['default'] ?? null);
        $this->fields[$name] = $field;
        if(!$this->options['groups']) $this->options['layout'][] = $name;
        return $this;
    }

    /**
     * Add fields to this form
     * 
     * @param array $fields
     */
    public function addFields(array $fields){
        foreach($fields as $name => $options){
            $this->addField($name, $options);
        }
    }

    /**
     * Check if this form is built
     *
     * @throws FormNotBuiltException
     * @return void
     */
    private function checkIfBuilt()
    {
        if(!$this->isBuilt()){
            throw new FormNotBuiltException("Form {$this->name} isn't finished building. Call \$form->end() before printing it.");
        }
    }

    /**
     * print form's opening
     *
     * @see  https://laravelcollective.com/docs/5.4/html
     * @throws FormNotBuiltException
     * @return void
     */
    public function renderStart()
    {
        echo FormFacade::open($this->attributes);
        echo FormFacade::hidden('_name', $this->name);
    }

    /**
     * prints form's closing
     *
     * @see  https://laravelcollective.com/docs/5.4/html
     * @return void
     */
    public function renderEnd()
    {
        echo FormFacade::close();
    }

    /**
     * Prints the submit button
     * 
     * @return string
     */
    public function renderSubmit()
    {   
        if(isset($this->options['submit']) and $this->options['submit']){
            echo FormFacade::submit(...$this->options['submit']);
        }
    }

    /**
     * Print all the layout of this form
     *
     * @throws FormNotBuiltException
     * @return void
     */
    public function renderLayout()
    {   
        if($this->options['layout']){
            foreach($this->options['layout'] as $name){
                $this->renderElement($name);
            }
        }
    }

    /**
     * Prints one element of this form (field or group)
     * 
     * @param  string $name
     */
    public function renderElement($name)
    {
        if($this->isGroup($name)) $this->renderGroup($name);
        else $this->renderField($name);
    }

    /**
     * Prints several elements of this form (field or group)
     * 
     * @param  array  $names  
     */
    public function renderElements(array $names)
    {
        foreach($names as $name){
            $this->renderElement($name);
        }
    }

    /**
     * Prints a group
     * 
     * @param  string $name
     * @return string
     */
    public function renderGroup($name)
    {
        echo view($this->options['groupView'], ['name' => $name, 'fields' => $this->options['groups'][$name], 'form' => $this])->render();
    }

    /**
     * Print one field of this form
     *
     * @see  https://laravelcollective.com/docs/5.4/html
     * @param  string $field
     * @throws FormNotBuiltException
     * @return void
     */
    public function renderField(string $field)
    {
        $this->fields[$field]->render();
    }

    /**
     * Prints this form
     * 
     * @return string
     */
    public function render()
    {
        $this->checkIfBuilt();
        echo view($this->options['view'], ['form' => $this])->render();
    }

    /**
     * Renders the form and returns as string
     * @return string
     */
    public function renderAsString()
    {
        $this->checkIfBuilt();
        return view($this->options['view'], ['form' => $this])->render();
    }

    public function __toString()
    {
        return $this->renderAsString();
    }

    // public function serialize()
    // {
    //     $data = [
    //         'name' => $this->name,
    //         'options' => $this->options,
    //         'attributes' => $this->attributes,
    //         'fields' => []
    //     ];
    //     foreach($this->fields as $field){
    //         $data['fields'][] = $field->serialize();
    //     }
    //     return $data;
    // }

}