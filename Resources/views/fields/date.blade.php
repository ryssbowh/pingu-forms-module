@extends('forms::field')

@section('inner')
{{ FormFacade::text($name, $default ?? null, $rendererAttributes) }}
@overwrite