@extends('forms::field')

@section('inner')
	{{ FormFacade::email($field->name, $field->getValue(), $field->attributes->toArray()) }}
@overwrite