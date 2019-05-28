<?php

return [
    'name' => 'Forms',
    'noValueLabel' => 'Select',
    'submit-default-classes' => '',
    'form-default-classes' => 'form',
    /**
     * Classes per form, indexed by the name of the form
     */
    'classes' => [],
    'field-default-classes' => 'form-group',
    /**
     * Classes for fields, index by field renderer name
     */
    'field-classes' => [],
    'label-default-classes' => 'label',
    /**
     * Classes for labels, index by field renderer name
     */
    'label-classes' => [],
    'field-inner-default-classes' => 'field-inner',
    /**
     * Classes for inner fields, index by field renderer name
     */
    'field-inner-classes' => [],
    'renderer-default-classes' => 'form-control',
    /**
     * Classes renderers, index by field renderer name
     */
    'renderer-classes' => [
        'text' => 'form-control',
        'select' => 'form-control',
        'password' => 'form-control',
        'datetime' => 'form-control js-datepicker',
        'email' => 'form-control',
        'textarea' => 'form-control',
        'checkbox' => 'form-check form-check-inline',
        'radio' => 'form-check form-check-inline'
    ]
];
