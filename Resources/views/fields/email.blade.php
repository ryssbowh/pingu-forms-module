@extends('forms::field')

@section('inner')
	{{ FormFacade::email($field->getName(), $field->getValue(), $field->attributes->toArray()) }}
@overwrite