@extends('forms::field')

@section('inner')
	{{ FormFacade::textarea($field->getHtmlName(), $field->getValue(), $attributes->toArray()) }}
@overwrite