@extends('forms@field')

@section('inner')
    {{ FormFacade::submit($field->option('label'), $attributes->toArray()) }}
@overwrite