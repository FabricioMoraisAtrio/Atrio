@extends('layouts.app')
@section('title', 'Rotina — Estudo de Caso')

@section('content')
@php
    $rotaNome       = 'secretaria.rotinas.documentos.estudo-caso';
    $corPrincipal   = '#7C3700';
    $bgPrincipal    = '#F5EDE6';
@endphp
@include('secretaria.rotinas.documentos._lista')
@endsection
