@extends('layouts.app')

@section('title', 'Gerenciar Clientes')
@section('plugins.Datatables', true)

@section('content_header_title', 'Gerenciar Clientes')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Clientes Cadastrados</h3>
            <div class="card-tools">
                <a href="{{ route('customers.create') }}" class="btn btn-primary btn-sm">Novo Cliente <i class="fas fa-plus"></i></a>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success m-3">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger m-3">{{ session('error') }}</div>
            @endif

            <table id="customers-table" class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Telefone</th>
                        <th>CPF</th>
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
    <script>
        $(document).ready(function() {
            $('.alert').delay(3000).fadeOut('slow');

            $('#customers-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                autoWidth: false,
                ajax: "{{ route('customers.index') }}",
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'nome', name: 'nome' },
                    { data: 'email', name: 'email' },
                    { data: 'telefone', name: 'telefone' },
                    { data: 'cpf', name: 'cpf' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' },
                ],
                language: {
                    url: "{{ asset('js/datatables/Portuguese-Brasil.json') }}"
                }
            });
        });
    </script>
@stop