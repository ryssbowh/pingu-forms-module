@extends('forms::field')

@section('inner')
	@foreach( $field->getItems() as $item)
		<div class="form-check form-check-inline">
			{{ FormFacade::checkbox($field->getName(), $item->getKey(), in_array($item->getKey(), $field->getValue()), $item->getAttributes()) }}
			<label class="form-check-label" for="{{ $item->getId() }}">{{ $item->getLabel() }}</label>
		</div>
	@endforeach
@overwrite