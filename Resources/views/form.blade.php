@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{ \FormFacade::open($attributes->all()) }}
<div class="fields">
    @if($form->hasGroups())
        @foreach($form->getGroups() as $group)
            {!! $group->render() !!}
        @endforeach
        @foreach($form->fieldsOutsideOfGroups() as $name)
            {!! $form->getElement($name)->render() !!}
        @endforeach 
    @else
        @foreach($form->getElements() as $element)
            {!! $element->render() !!}
        @endforeach
    @endif
</div>
{{ \FormFacade::close() }}