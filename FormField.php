<?php

namespace Pingu\Forms;

use Illuminate\Support\Arr;
use Pingu\Field\Contracts\FieldContract;

class FormField
{
    protected $widgets = [];

    public function registerField(string $field)
    {
        app()->bind('forms.fields.'.$field::machineName(), function () use ($field) {
            return $field;
        });
        app()->bind('forms.fields.'.$field::machineName().'.options', function () use ($field) {
            return $field::options();
        });
    }

    public function registerFields(array $fields)
    {
        foreach ($fields as $field) {
            $this->registerField($field);
        }
    }

    public function getRegisteredField(string $name)
    {
        return app()['forms.fields.'.$name];
    }

    public function getRegisteredOptions(string $field)
    {
        if (class_exists($field)) {
            $field = $field::machineName();
        }
        return app()['forms.fields.'.$field.'.options'];
    }

    public function registerWidgets(string $field, $widgets)
    {
        $widgets = Arr::wrap($widgets);
        $widgets = array_map(function ($widget) {
            return $widget::machineName();
        }, $widgets);
        $this->widgets[$field] = array_merge($this->widgets[$field] ?? [], $widgets);
    }

    public function defaultWidget(string $field)
    {
        return $this->widgets[$field][0] ?? null;
    }

    public function availableWidgets(FieldContract $field)
    {
        if (!isset($this->widgets[get_class($field)])) {
            return [];
        }
        $out = [];
        foreach ($this->widgets[get_class($field)] as $name) {
            $class = $this->getRegisteredField($name);
            $out[$name] = $class::friendlyName();
        }
        return $out;
    }
}