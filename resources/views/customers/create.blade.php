@extends('layouts.app')

@section('content_header_title', 'Criar Novo Cliente')

@section('content')
    <div class="card">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Erros de Validação!</h5>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('customers.store') }}" method="POST">
                @csrf
                @include('customers._form')
                <button type="submit" class="btn btn-primary">Salvar</button>
                <a href="{{ route('customers.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
@stop