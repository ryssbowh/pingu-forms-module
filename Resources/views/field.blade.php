<div class="{{ $field->classes->toString() }} {{ isset($errors) and $errors->has($field->getName()) ? ' is-invalid' : '' }}">
	@if($field->label)
		<div class="{{ $field->labelClasses->toString() }}">{{ $field->label }}@if(isset($field->required) and $field->required) *@endif</div>
	@endif
	<div class="{{ $field->innerClasses->toString() }}">
		@yield('inner')
	</div>
</div>