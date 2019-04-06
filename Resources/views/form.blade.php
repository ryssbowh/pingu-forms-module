@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{ $form->printStart() }}
<div class="fields">
	{{ $form->printLayout() }}
</div>
{{ $form->printSubmit() }}
{{ $form->printEnd() }}