<div class="actions">
	@foreach($actions as $name => $action)
		<div class="action {{ $name }}">
			{{ FormFacade::{$action['type']}($action['label'], $action['attributes']) }}
		</div>
	@endforeach
</div>