@extends('layouts.app')
@section('title', 'Rotina — PAEE')

@section('content')
@php
    $rotaNome       = 'secretaria.rotinas.documentos.paee';
    $corPrincipal   = '#009C8C';
    $bgPrincipal    = '#E6F5F4';
@endphp
@include('secretaria.rotinas.documentos._lista')
@endsection
