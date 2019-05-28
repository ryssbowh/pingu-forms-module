@extends('forms::field')

@section('inner')
<div class="form-check form-check-inline">
	{{ FormFacade::hidden($name, 0, ['class' => 'noPopulation']) }}
	{{ FormFacade::checkbox($name, 1, (bool)$default, $rendererAttributes) }}
</div>
@overwrite