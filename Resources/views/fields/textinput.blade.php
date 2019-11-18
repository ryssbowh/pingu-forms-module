@extends('forms::field')

@section('inner')
	{{ FormFacade::text($field->getHtmlName(), $field->getValue(), $attributes->toArray()) }}
@overwrite