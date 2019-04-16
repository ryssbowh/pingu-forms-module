<div class="form-group row field-{{ $type }}{{ $errors->has($name) ? ' is-invalid' : '' }}">
	@if($label)
		<div class="label col-md-4">{{ $label }}</div>
	@endif
	<div class="col-md-8">
		@foreach( $checkboxes as $key => $checkbox)
			<div class="form-check form-check-inline">
				{{ $checkbox }} 
				<label class="form-check-label" for="{{ $name.$key}}">{{ $items[$key] }}</label>
			</div>
		@endforeach
	</div>
</div>