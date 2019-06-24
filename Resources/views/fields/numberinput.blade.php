@extends('forms::field')

@section('inner')
	{{ FormFacade::number($field->getName(), $field->getValue(), $field->attributes->toArray()) }}
@overwrite