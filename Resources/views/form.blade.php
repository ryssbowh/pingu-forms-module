@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{ $form->renderStart() }}
<div class="fields">
    @foreach($form->getElements() as $element)
	   {!! $element->render() !!}
    @endforeach
</div>
{{ $form->renderEnd() }}