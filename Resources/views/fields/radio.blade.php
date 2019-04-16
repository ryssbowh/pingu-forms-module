<div class="form-group row field-{{ $type }}{{ $errors->has($name) ? ' is-invalid' : '' }}">
	@if($label)
		<div class="label col-md-4">{{ $label }}</div>
	@endif
	<div class="col-md-8">
		@foreach( $radios as $key => $radio)
			<div class="form-check form-check-inline">
				{{ $radio }} 
				<label class="form-check-label" for="{{ $name.$items[$key]['id'] }}">{{ $items[$key]['label'] }}</label>
			</div>
		@endforeach
	</div>
</div>