@extends('forms::field')

@section('inner')
	{{ FormFacade::text($field->getName(), $field->getValue(), $field->getAttributes()) }}
@overwrite