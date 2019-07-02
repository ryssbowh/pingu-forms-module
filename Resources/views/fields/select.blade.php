@extends('forms::field')

@section('inner')
	{{ FormFacade::select($field->getName(), $field->getItems(), $field->getValue(), $field->attributes->toArray()) }}	
@overwrite