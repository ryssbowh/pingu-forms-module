@extends('forms::field')

@section('inner')
{{ FormFacade::date($name, $default ?? null, $rendererAttributes) }}
@overwrite