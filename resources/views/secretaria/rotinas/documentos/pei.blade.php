@extends('layouts.app')
@section('title', 'Rotina — PEI')

@section('content')
@php
    $rotaNome       = 'secretaria.rotinas.documentos.pei';
    $corPrincipal   = '#004B8D';
    $bgPrincipal    = '#E8F0F9';
@endphp
@include('secretaria.rotinas.documentos._lista')
@endsection
