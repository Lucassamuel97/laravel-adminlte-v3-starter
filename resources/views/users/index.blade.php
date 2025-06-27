@extends('layouts.app')

@section('title', 'Gerenciar Usuários')

@section('css')
    <link rel="stylesheet" href="{{ asset('vendor/datatables/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/datatables-plugins/responsive/css/responsive.bootstrap4.min.css') }}">
@stop

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
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success m-3">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger m-3">{{ session('error') }}</div>
            @endif

            <table id="users-table" class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Nível de Acesso</th>
                        <th class="text-center" style="width: 150px;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('js')
    <script src="{{ asset('vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables-plugins/responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables-plugins/responsive/js/responsive.bootstrap4.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('.alert').delay(3000).fadeOut('slow');

            // Inicializa o DataTables
            $('#users-table').DataTable({
                processing: true, // Mostra o indicador de processamento
                serverSide: true, // Habilita o processamento do lado do servidor
                responsive: true, // Habilita responsividade
                autoWidth: false, // Desabilita auto-ajuste de largura para melhor responsividade
                ajax: "{{ route('users.index') }}", // URL para a requisição AJAX
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'role_badge', name: 'role', orderable: true, searchable: true }, // Mapeia para a nova coluna 'role_badge'
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' },
                ],
                language: {
                    url: "{{ asset('js/datatables/Portuguese-Brasil.json') }}"
                }
            });
        });
    </script>
@stop
