<?php
namespace Pingu\Forms\Support;

use Pingu\Forms\Contracts\FormContract;
use Pingu\Forms\Contracts\HasFieldsContract;
use Pingu\Forms\Traits\Form as FormTrait;

abstract class Form
{
    use FormTrait;

    /**
     * Name of that field. all non alphanumeric character will be removed.
     * (hyphens will be kept)
     * 
     * @return string
     */
    protected abstract function name(): string;

    /**
     * Method for that form (PUT POST GET DELETE PATCH
     * 
     * @return string
     */
    protected abstract function method(): string;

    /**
     * Url for that form, can be an url, an action or a route
     *
     * @see    https://github.com/LaravelCollective/docs/blob/5.6/html.md#opening-a-form
     * @return array
     */
    protected abstract function action(): array;

    /**
     * Fields definitions
     * 
     * @return array
     */
    protected abstract function elements(): array;

    /**
     * Attributes for the form
     * 
     * @return array
     */
    protected function attributes(): array
    {
        return [];
    }

    /**
     * Options for the form
     * 
     * @return array
     */
    protected function options(): array
    {
        return [];
    }

    /**
     * Groups for the form
     * 
     * @return array
     */
    protected function groups(): array
    {
        return [];
    }
    
}