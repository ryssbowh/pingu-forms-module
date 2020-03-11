@extends('forms@field')

@section('inner')
	{{ FormFacade::email($field->getHtmlName(), $field->getValue(), $attributes->toArray()) }}
@overwrite