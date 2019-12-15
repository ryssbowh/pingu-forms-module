@extends('forms::field')

@section('inner')
	<div id="{{ $field->getName() }}" class="input-group" data-target-input="nearest" data-format="{{ $field->option('format') }}">
		<input type="text" {!! $attributes->toHtml() !!} data-target="#{{ $field->getName() }}" value="{{ $field->getValue() }}" name="{{ $field->getName() }}"/>
		<div class="input-group-append" data-target="#{{ $field->getName() }}" data-toggle="datetimepicker">
			<div class="input-group-text"><i class="fa fa-calendar"></i></div>
		</div>
	</div>
@overwrite