@extends('adminlte::page')

@section('title', 'Gerenciar Usuários')

@section('content_header')
    <h1>Gerenciar Usuários</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Usuários Cadastrados</h3>
            <div class="card-tools">
                <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">Novo Usuário <i class="fas fa-plus"></i></a>
            </div>
        </div>
        <div class="card-body p-0">
            @if (session('success'))
                <div class="alert alert-success m-3">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger m-3">{{ session('error') }}</div>
            @endif

            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Nível de Acesso</th>
                        <th style="width: 150px;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td><span class="badge {{ $user->isAdmin() ? 'badge-info' : 'badge-secondary' }}">{{ ucfirst($user->role) }}</span></td>
                            <td>
                                <a href="{{ route('users.show', $user->id) }}" class="btn btn-info btn-xs" title="Ver Detalhes"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-xs" title="Editar Usuário"><i class="fas fa-edit"></i></a>
                                {{-- Apenas admins podem excluir, e não podem excluir a si mesmos --}}
                                @can('delete', $user)
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja excluir este usuário? Esta ação não pode ser desfeita.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-xs" title="Excluir Usuário"><i class="fas fa-trash"></i></button>
                                    </form>
                                @else
                                    <button type="button" class="btn btn-danger btn-xs" disabled title="Você não tem permissão para excluir este usuário ou não pode excluir a si mesmo."><i class="fas fa-ban"></i></button>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Nenhum usuário encontrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer clearfix">
            {{ $users->links('pagination::bootstrap-4') }}
        </div>
    </div>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            $('.alert').delay(3000).fadeOut('slow');
        });
    </script>
@stop