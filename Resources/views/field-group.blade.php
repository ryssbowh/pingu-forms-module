<div class="{{ $classes }}">
	@if($label = $group->option('label'))
		<label class="{{ $labelClasses }}">
            {{ $label }}
            @if($helper = $group->option('helper'))
                <div class="helper">{!! $helper !!}</div>
            @endif
		</label>
	@endif
    @foreach($fields as $field)
        {!! $field !!}
    @endforeach
</div>