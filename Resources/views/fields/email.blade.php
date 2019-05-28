@extends('forms::field')

@section('inner')
{{ FormFacade::email($name, $default ?? null, $rendererAttributes) }}
@overwrite