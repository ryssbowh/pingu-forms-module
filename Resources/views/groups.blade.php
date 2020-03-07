@foreach($groups as $name => $fields)
	<fieldset class="form-group form-group-{{ $name }}">
		@foreach($fields as $name)
            @if($form->hasElement($name))
                {{ $form->getElement($name)->render() }}
            @endif
		@endforeach
	</fieldset>
@endforeach
@foreach($form->fieldsOutsideOfGroups() as $name)
    {{ $form->getElement($name)->render() }}
@endforeach 