@extends('forms@field')

@section('inner')
	<div class="checkboxes-item">
		{{ FormFacade::hidden($field->getHtmlName(), 0, ['class' => 'noPopulation']) }}
        {{ FormFacade::checkbox($field->getHtmlName(), 1, $field->getValue(), $attributes->toArray()) }}
        @if($field->option('useLabel'))
            <label for="{{ $field->getName().$field->option('index') }}">{{ $field->option('label') }}</label>
        @endif
	</div>
@overwrite