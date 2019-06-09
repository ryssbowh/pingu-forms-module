<?php

return [
    'name' => 'Forms',
    'generator' =>[
        /**
         * Path to generate Form classes
         */
        'paths' => [
            'forms' => 'Forms'
        ]
    ],
    'noValueLabel' => 'Select',
    /**
     * Classes per form, indexed by the name of the form
     */
    'classes' => [],
    /**
     * default classes for form
     */
    'default-classes' => 'form',
    /**
     * Classes for fields, index by field renderer name
     */
    'field-classes' => [],
    /**
     * Extra classes for fields
     */
    'field-default-classes' => 'form-field',
    /**
     * Classes for labels, index by field renderer name
     */
    'label-classes' => [],
    /**
     * Default classes for label
     */
    'label-default-classes' => 'field-label',
    /**
     * Field inner classes index by field renderer name
     */
    'field-inner-classes' => [],
    /**
     * field inner default classes
     */
    'field-inner-default-classes' => 'field-inner',
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
    ],
    /**
     * Class for actions, per action type
     */
    'action-classes' => [],
    /**
     * Default actions classes
     */
    'action-default-classes' => 'btn btn-primary',
];
