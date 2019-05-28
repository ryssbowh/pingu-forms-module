@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{ $form->renderStart() }}

<div class="fields">
	{{ $form->renderLayout() }}
</div>
{{ $form->renderSubmit() }}
{{ $form->renderEnd() }}