<fieldset class="{{ $classes }}">
    @if($group->option('showLabel'))
        <div class="{{ $labelClasses }}">{{ $group->option('label') }}</div>
    @endif
    <div class="form-group-fields">
        @foreach($fields as $name)
            @if($form->hasElement($name))
                {!! $form->getElement($name)->render() !!}
            @endif
        @endforeach
    </div>
</fieldset>