@extends('forms::field')

@section('inner')
	{{ FormFacade::password($field->getName(), $field->attributes->toArray()) }}
@overwrite
