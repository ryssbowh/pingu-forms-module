@extends('forms::field')

@section('inner')
	{{ FormFacade::textarea($field->getName(), $field->getValue(), $field->attributes->toArray()) }}
@overwrite