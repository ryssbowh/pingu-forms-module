@foreach($groups as $name => $fields)
	<fieldset class="form-group form-group-{{ $name }}">
		@foreach($fields as $name)
			{{ $form->getElement($name)->render() }}
		@endforeach
        @foreach($form->fieldsOutsideOfGroups() as $name)
            {{ $form->getElement($name)->render() }}
        @endforeach 
	</fieldset>
@endforeach