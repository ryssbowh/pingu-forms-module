<?php

namespace Pingu\Forms;

use Illuminate\Support\Arr;
use Pingu\Field\Contracts\FieldContract;
use Pingu\Forms\Exceptions\FormWidgetsException;

class FormField
{
    /**
     * widgets (Form fields) to display a Field
     * 
     * @var array
     */
    protected $widgets = [];

    /**
     * Widgets (Form fields) to display a Field as a filter
     * 
     * @var array
     */
    protected $filterWidgets = [];

    /**
     * Registers a form field (and its options class) into laravel container
     * 
     * @param string $field
     */
    public function registerField(string $field)
    {
        app()->bind('forms.fields.'.$field::machineName(), function () use ($field) {
            return $field;
        });
        app()->bind('forms.fields.'.$field::machineName().'.options', function () use ($field) {
            return $field::options();
        });
    }

    /**
     * Registers form fields classes
     * 
     * @param  string|array $fields
     */
    public function registerFields($fields)
    {
        $fields = Arr::wrap($fields);
        foreach ($fields as $field) {
            $this->registerField($field);
        }
    }

    /**
     * registered field class getter
     * 
     * @param string $name
     * 
     * @return string
     */
    public function getRegisteredField(string $name)
    {
        return app()['forms.fields.'.$name];
    }

    /**
     * registered field options class getter
     * 
     * @param string $field
     * 
     * @return string
     */
    public function getRegisteredOptions(string $field)
    {
        if (class_exists($field)) {
            $field = $field::machineName();
        }
        return app()['forms.fields.'.$field.'.options'];
    }

    /**
     * Registers widgets (form fields) for a field
     * 
     * @param string $field
     * 
     * @param string|array $widgets
     */
    public function registerWidgets(string $field, $widgets)
    {
        $widgets = Arr::wrap($widgets);
        $widgets = array_map(function ($widget) {
            return $widget::machineName();
        }, $widgets);
        $this->widgets[$field] = array_merge($this->widgets[$field] ?? [], $widgets);
    }

    /**
     * Get default widget (form field) for a field
     * 
     * @param string $field
     * 
     * @throws FormWidgetsException
     * 
     * @return string
     */
    public function defaultWidget(string $field)
    {
        if (!isset($this->widgets[$field][0])) {
            throw FormWidgetsException::noWidgets($field);
        }
        return $this->widgets[$field][0] ?? null;
    }

    /**
     * Get available widgets (form fields) for a field
     * @param FieldContract $field
     * @throws FormWidgetsException
     * 
     * @return array
     */
    public function availableWidgets(FieldContract $field)
    {
        if (!isset($this->widgets[get_class($field)])) {
            throw FormWidgetsException::noWidgets(get_class($field));
        }
        $out = [];
        foreach ($this->widgets[get_class($field)] as $name) {
            $class = $this->getRegisteredField($name);
            $out[$name] = $class::friendlyName();
        }
        return $out;
    }

    /**
     * Registers widgets (form fields) for a field
     * 
     * @param string $field
     * @param string|array $widgets
     */
    public function registerFilterWidgets(string $field, $widgets)
    {
        $widgets = Arr::wrap($widgets);
        $widgets = array_map(function ($widget) {
            return $widget::machineName();
        }, $widgets);
        $this->filterWidgets[$field] = array_merge($this->filterWidgets[$field] ?? [], $widgets);
    }

    /**
     * Get default widget (form field) for a field to be displayed as a filter
     * 
     * @param string $field
     * 
     * @throws FormWidgetsException
     * 
     * @return string
     */
    public function defaultFilterWidget(string $field)
    {
        if (!isset($this->filterWidgets[$field][0])) {
            throw FormWidgetsException::noFilterWidgets($field);
        }
        return $this->filterWidgets[$field][0] ?? null;
    }

    /**
     * Get available widgets (form fields) for a field to be displayed as a filter
     * 
     * @param FieldContract $field
     * 
     * @throws FormWidgetsException
     * 
     * @return array
     */
    public function availableFilterWidgets(FieldContract $field)
    {
        if (!isset($this->filterWidgets[get_class($field)])) {
            throw FormWidgetsException::noFilterWidgets(get_class($field));
        }
        $out = [];
        foreach ($this->filterWidgets[get_class($field)] as $name) {
            $class = $this->getRegisteredField($name);
            $out[$name] = $class::friendlyName();
        }
        return $out;
    }
}