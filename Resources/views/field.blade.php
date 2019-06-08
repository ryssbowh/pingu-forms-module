<div class="{{ $fieldClasses }} field-{{ $type }}{{ isset($errors) and $errors->has($name) ? ' is-invalid' : '' }}">
	@if($label)
		<div class="{{ $labelClasses }}">{{ $label }}@if(isset($required) and $required) *@endif</div>
	@endif
	<div class="{{ $fieldInnerClasses }}">
		@yield('inner')
	</div>
</div>