@foreach($groups as $name => $fields)
	<div class="form-group form-group-{{ $name }}">
		@foreach($fields as $field)
			{{ $field->render() }}
		@endforeach
	</div>
@endforeach