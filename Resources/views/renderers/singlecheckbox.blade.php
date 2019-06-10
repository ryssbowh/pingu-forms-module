<div class="form-check form-check-inline">
	{{ FormFacade::hidden($renderer->name, 0, ['class' => 'noPopulation']) }}
	{{ FormFacade::checkbox($renderer->name, 1, $renderer->getValue(), $renderer->attributes->toArray()) }}
</div>