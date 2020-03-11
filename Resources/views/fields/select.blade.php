@extends('forms@field')

@section('inner')
	{{ FormFacade::select($field->getHtmlName(), $items, $field->getValue(), $attributes->toArray()) }}	
@overwrite