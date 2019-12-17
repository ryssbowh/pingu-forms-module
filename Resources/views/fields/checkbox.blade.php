@extends('forms::field')

@section('inner')
	<div class="checkboxes-item">
		{{ FormFacade::hidden($field->getName(), 0, ['class' => 'noPopulation']) }}
        {{ FormFacade::checkbox($field->getName(), 1, $field->getValue(), $attributes->toArray()) }}
        @if($field->option('useLabel'))
            <label for="{{ $field->getName().$field->getIndex() }}">{{ $field->option('label') }}</label>
        @endif
	</div>
@overwrite