<div class="{{ $field->wrapperClasses }} {{ isset($errors) and $errors->has($field->getName()) ? ' is-invalid' : '' }}">
	@if($label = $field->option('label'))
		{{ FormFacade::label($label.($field->attribute('required') ? ' *' : ''), null, ['class' => $field->labelClasses]) }}
	@endif
	@yield('inner')
</div>