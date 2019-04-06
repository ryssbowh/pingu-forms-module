<div class="form-group row field-{{ $type }}{{ $errors->has($name) ? ' is-invalid' : '' }}">
	@if($label)
		<div class="label col-md-4">{{ $label }}</div>
	@endif
	@yield('field-inner')
</div>