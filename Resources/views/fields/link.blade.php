@extends('forms@field')

@section('inner')
    <a href="{{ $field->option('url') }}" {!! $attributes->toHtml() !!}>{{ $field->option('label') }}</a>
@overwrite