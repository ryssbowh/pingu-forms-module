@extends('forms@field')

@section('inner')
    <div class="checkboxes-items">
    	@foreach( $field->getItems()->getItems() as $item)
    		<div class="checkboxes-item">
    			{{ FormFacade::checkbox($field->getHtmlName(), $item->getKey(), in_array($item->getKey(), $field->getValue()), $item->getAttributes()) }}
    			<label for="{{ $item->getId() }}">{{ $item->getLabel() }}</label>
    		</div>
    	@endforeach
    </div>
@overwrite