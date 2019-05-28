@extends('forms::field')

@section('inner')
@foreach( $checkboxes as $key => $checkbox)
	<div class="form-check form-check-inline">
		{{ FormFacade::checkbox($name, $key, $checkbox['checked'], $checkbox['attributes']) }}
		<label class="form-check-label" for="{{ $checkbox['attributes']['id'] }}">{{ $checkbox['label'] }}</label>
	</div>
@endforeach
@overwrite