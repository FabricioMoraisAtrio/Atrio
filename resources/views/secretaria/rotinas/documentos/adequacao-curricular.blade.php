@extends('layouts.app')
@section('title', 'Rotina — Adequação Curricular')

@section('content')
@php
    $rotaNome       = 'secretaria.rotinas.documentos.adequacao-curricular';
    $corPrincipal   = '#B45309';
    $bgPrincipal    = '#FEF3C7';
@endphp
@include('secretaria.rotinas.documentos._lista')
@endsection
