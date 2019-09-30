@foreach($groups as $name => $fields)
	<fieldset class="form-group form-group-{{ $name }}">
		@foreach($fields as $field)

			{{ $field->render() }}
		@endforeach
	</fieldset>
@endforeach