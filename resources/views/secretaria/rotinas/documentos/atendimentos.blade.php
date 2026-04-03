@extends('layouts.app')
@section('title', 'Rotina — Atendimentos')

@section('content')
@php
    $rotaNome       = 'secretaria.rotinas.documentos.atendimentos';
    $corPrincipal   = '#6D28D9';
    $bgPrincipal    = '#EDE9FE';
@endphp
@include('secretaria.rotinas.documentos._lista')
@endsection
