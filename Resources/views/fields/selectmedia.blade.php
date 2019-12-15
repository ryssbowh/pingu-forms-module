@extends('forms::field')

@section('inner')
    @if($media = $field->getMedia())
        <img src="{{ $media->url('icon') }}"><br/>
    @endif
    {{ FormFacade::file($field->getName(), $attributes->toArray()) }}
@overwrite