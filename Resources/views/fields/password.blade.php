@extends('forms@field')

@section('inner')
	{{ FormFacade::password($field->getHtmlName(), $attributes->toArray()) }}
@overwrite
