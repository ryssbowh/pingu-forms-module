@extends('forms@field')

@section('inner')
	{{ FormFacade::number($field->getHtmlName(), $field->getValue(), $attributes->toArray()) }}
@overwrite