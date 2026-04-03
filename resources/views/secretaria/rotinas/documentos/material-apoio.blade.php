@extends('layouts.app')
@section('title', 'Rotina — Material de Apoio')

@section('content')
@php
    $rotaNome       = 'secretaria.rotinas.documentos.material-apoio';
    $corPrincipal   = '#065F46';
    $bgPrincipal    = '#ECFDF5';
@endphp
@include('secretaria.rotinas.documentos._lista')
@endsection
