<?php

return [
    'name' => 'Forms',
    'useCache' => !env('APP_DEBUG'),
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
     * Classes for forms, indexed by the name of the form
     */
    'classes' => [
    ],
    /**
     * default classes for form
     */
    'default-classes' => 'form',
    /**
     * Classes for fields, index by field name
     */
    'field-classes' => [
        'default' => [
            'textinput' => 'form-control',
            'select' => 'form-control',
            'password' => 'form-control',
            'datetime' => 'form-control',
            'email' => 'form-control',
            'textarea' => 'form-control',
            'checkbox' => 'form-check form-check-inline',
            'radio' => 'form-check form-check-inline',
            'submit' => 'btn btn-primary'
        ]
    ],
    /**
     * Extra classes for fields
     */
    'field-default-classes' => 'field-inner',
    /**
     * Classes for labels, index by field name
     */
    'label-classes' => [],
    /**
     * Default classes for label
     */
    'label-default-classes' => 'label',
    /**
     * Field wrapper classes index by field name
     */
    'wrapper-classes' => [],
    /**
     * field wrapper default classes
     */
    'wrapper-default-classes' => 'field',
];
