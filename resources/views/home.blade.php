@extends('layouts.app')

{{-- Customize layout sections --}}

@section('subtitle', 'Dashboard')

{{-- Set the title and subtitle for the content header --}}
@section('content_header_title', 'Home')
@section('content_header_subtitle', 'Dashboard')

{{-- Content body: main page content --}}

@section('content_body')
    <div class="row">
        {{-- Total de Produtos --}}
        <div class="col-lg-3 col-6">
            <x-adminlte-info-box title="Produtos Cadastrados" text="{{ $totalProducts }}" icon="fas fa-box" theme="primary" url="{{ route('products.index') }}" url-text="Ver Produtos"/>
        </div>
        {{-- Total de Usuários --}}
        <div class="col-lg-3 col-6">
            <x-adminlte-info-box title="Usuários Cadastrados" text="{{ $totalUsers }}" icon="fas fa-users" theme="info" url="{{ route('users.index') }}" url-text="Gerenciar Usuários" />
        </div>
    </div>
@stop

{{-- Push extra CSS --}}

@push('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@endpush

{{-- Push extra scripts --}}

<!-- @push('js')
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@endpush -->