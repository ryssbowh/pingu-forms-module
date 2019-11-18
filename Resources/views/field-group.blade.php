<div class="{{ $classes }}">
	@if($label = $group->option('label'))
		<label class="{{ $labelClasses }}">
            {{ $label }}
            @if($helper = $group->option('helper'))
                <div class="helper">{!! $helper !!}</div>
            @endif
		</label>
        @foreach($fields as $field)
            {!! $field !!}
        @endforeach
	@endif
</div>