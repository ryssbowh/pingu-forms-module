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
	{{ $form->renderGroups() }}
</div>
{{ $form->renderActions() }}
{{ $form->renderEnd() }}