@extends('forms::field')

@section('inner')
{{ FormFacade::number($name, $default ?? null, $rendererAttributes) }}
@overwrite