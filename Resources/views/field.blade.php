<div class="{{ $wrapperClasses->toHtml() }} {{ isset($errors) and $errors->has($field->getName()) ? ' is-invalid' : '' }}">
	@if($field->option('showLabel'))
        <?php $label = $field->option('label'); ?>
		<label class="{{ $labelClasses->toHtml() }}">{{ $label.($attributes->get('required') ? ' *' : '') }}
			@if($helper = $field->option('helper'))
				<div class="helper">{!! $helper !!}</div>
			@endif
		</label>
	@endif
	@yield('inner')
</div>