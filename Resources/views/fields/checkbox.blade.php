@extends('forms::field')

@section('inner')
	<div class="form-check form-check-inline">
		{{ FormFacade::hidden($field->getName(), 0, ['class' => 'noPopulation']) }}
		{{ FormFacade::checkbox($field->getName(), 1, $field->getValue(), $attributes->toArray()) }}
	</div>
@overwrite