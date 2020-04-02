@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{ \FormFacade::open($attributes) }}
<div class="fields">
    @if($hasGroups)
        @include('forms@groups')
    @else
        @foreach($elements as $element)
            {{ $element->render() }}
        @endforeach
    @endif
</div>
{{ \FormFacade::close() }}