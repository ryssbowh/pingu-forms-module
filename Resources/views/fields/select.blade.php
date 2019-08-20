@extends('forms::field')

@section('inner')
	{{ FormFacade::select($field->getHtmlName(), $field->getItems(), $field->getValue(), $field->getAttributes()) }}	
@overwrite