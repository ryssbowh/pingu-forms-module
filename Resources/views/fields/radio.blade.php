@extends('forms::field')

@section('inner')
@foreach($radios as $key => $radio)
	<div class="form-check form-check-inline">
		{{ FormFacade::radio($name, $key, $radio['checked'], $radio['attributes']) }}
		<label class="form-check-label" for="{{ $radio['attributes']['id'] }}">{{ $radio['label'] }}</label>
	</div>
@endforeach
@overwrite