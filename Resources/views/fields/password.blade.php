@extends('forms::field')

@section('inner')
{{ FormFacade::password($field->name, $field->attributes->toArray()) }}
@overwrite