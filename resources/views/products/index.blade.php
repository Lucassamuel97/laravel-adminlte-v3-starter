@extends('layouts.app')

@section('title', 'Produtos')

@section('content_header')
    <h1>Lista de Produtos</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Produtos Cadastrados</h3>
            <div class="card-tools">
                <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm">Novo Produto <i class="fas fa-plus"></i></a>
            </div>
        </div>
        <div class="card-body p-0">
            {{-- Mensagens de sucesso e erro --}}
            @if (session('success'))
                <div class="alert alert-success m-3">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger m-3">
                    {{ session('error') }}
                </div>
            @endif

            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th style="width: 50px;">ID</th>
                        <th>Nome</th>
                        <th>Preço</th>
                        <th>Estoque</th>
                        <th>Criado Por</th> {{-- Adicionado --}}
                        <th style="width: 150px;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>{{ $product->name }}</td>
                            <td>R$ {{ number_format($product->price, 2, ',', '.') }}</td>
                            <td>{{ $product->stock }}</td>
                            <td>{{ $product->user->name ?? 'N/A' }}</td> {{-- Mostra o nome do criador --}}
                            <td>
                                <a href="{{ route('products.show', $product->id) }}" class="btn btn-info btn-xs" title="Ver Detalhes"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-xs @cannot('update', $product) disabled @endcannot" title="Editar Produto">
                                    <i class="fas fa-edit"></i>
                                </a>
                                {{-- Botão de Excluir com verificação de Policy --}}
                                @can('delete', $product)
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja excluir este produto? Esta ação não pode ser desfeita.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-xs" title="Excluir Produto"><i class="fas fa-trash"></i></button>
                                    </form>
                                @else
                                    {{-- Botão desabilitado ou mensagem para usuários sem permissão --}}
                                    <button type="button" class="btn btn-danger btn-xs" disabled title="Você não tem permissão para excluir este produto."><i class="fas fa-ban"></i></button>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Nenhum produto encontrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer clearfix">
            {{ $products->links('pagination::bootstrap-4') }}
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