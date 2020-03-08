@extends('forms::field')

@section('inner')
	<div id="{{ $attributes->get('id') }}" class="input-group date" data-target-input="nearest" data-format="{{ $field->option('format') }}">
		<input type="text" {!! $attributes->toHtml() !!} data-target="#{{ $attributes->get('id') }}" value="{{ $field->getValue() }}" name="{{ $field->getHtmlName() }}"/>
		<div class="input-group-append" data-target="#{{ $attributes->get('id') }}" data-toggle="datetimepicker">
			<div class="input-group-text"><i class="fa fa-calendar"></i></div>
		</div>
	</div>
@overwrite