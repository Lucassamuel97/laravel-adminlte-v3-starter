@extends('layouts.app')

@section('title', 'Editar Cliente')

@section('content_header_title', 'Editar Cliente')
@section('content_header_subtitle',  $customer->nome ?? 'Cliente')

@section('content')
    <div class="card">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('customers.update', $customer->id) }}" method="POST">
                @csrf
                @method('PUT')
                @include('customers._form')
                <button type="submit" class="btn btn-primary">Atualizar</button>
                <a href="{{ route('customers.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
@stop