@extends('forms@field')

@section('inner')
	{{ FormFacade::select($field->getHtmlName(), $field->getItems()->toArray(), $field->getValue(), $attributes->toArray()) }}	
@overwrite