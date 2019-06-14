@extends('forms::field')

@section('inner')
	@foreach( $field->getItems() as $key => $label)
		<div class="form-check form-check-inline">
			{{ FormFacade::checkbox($field->getName(), $key, in_array($key, $field->getValue()), ['id' => 'field-'.$field->getName().'-'.$key]) }}
			<label class="form-check-label" for="field-{{ $field->getName().'-'.$key }}">{{ $label }}</label>
		</div>
	@endforeach
@overwrite