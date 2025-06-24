@extends('adminlte::page')

@section('title', 'Detalhes do Usuário')

@section('content_header')
    <h1>Detalhes do Usuário: {{ $user->name }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Informações do Usuário</h3>
        </div>
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">ID:</dt>
                <dd class="col-sm-9">{{ $user->id }}</dd>

                <dt class="col-sm-3">Nome:</dt>
                <dd class="col-sm-9">{{ $user->name }}</dd>

                <dt class="col-sm-3">Email:</dt>
                <dd class="col-sm-9">{{ $user->email }}</dd>

                <dt class="col-sm-3">Nível de Acesso:</dt>
                <dd class="col-sm-9"><span class="badge {{ $user->isAdmin() ? 'badge-info' : 'badge-secondary' }}">{{ ucfirst($user->role) }}</span></dd>

                <dt class="col-sm-3">Criado em:</dt>
                <dd class="col-sm-9">{{ $user->created_at->format('d/m/Y H:i:s') }}</dd>

                <dt class="col-sm-3">Atualizado em:</dt>
                <dd class="col-sm-9">{{ $user->updated_at->format('d/m/Y H:i:s') }}</dd>
            </dl>
            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning"><i class="fas fa-edit"></i> Editar</a>
            @can('delete', $user)
                <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja excluir este usuário? Esta ação não pode ser desfeita.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> Excluir</button>
                </form>
            @endcan
            <a href="{{ route('users.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Voltar para a Lista</a>
        </div>
    </div>
@stop