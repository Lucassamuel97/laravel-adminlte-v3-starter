@extends('layouts.app')

@section('title', 'Detalhes do Produto')

@section('content_header_title', 'Detalhes do Produto')
@section('content_header_subtitle',  $product->name ?? 'Produto')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Informações do Produto</h3>
        </div>
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">ID:</dt>
                <dd class="col-sm-9">{{ $product->id }}</dd>

                <dt class="col-sm-3">Nome:</dt>
                <dd class="col-sm-9">{{ $product->name }}</dd>

                <dt class="col-sm-3">Descrição:</dt>
                <dd class="col-sm-9">{{ $product->description ?? 'N/A' }}</dd>

                <dt class="col-sm-3">Preço:</dt>
                <dd class="col-sm-9">R$ {{ number_format($product->price, 2, ',', '.') }}</dd>

                <dt class="col-sm-3">Estoque:</dt>
                <dd class="col-sm-9">{{ $product->stock }}</dd>

                <dt class="col-sm-3">Criado Por:</dt>
                <dd class="col-sm-9">{{ $product->user->name ?? 'N/A' }}</dd> {{-- Mostra o nome do criador --}}

                <dt class="col-sm-3">Criado em:</dt>
                <dd class="col-sm-9">{{ $product->created_at->format('d/m/Y H:i:s') }}</dd>

                <dt class="col-sm-3">Atualizado em:</dt>
                <dd class="col-sm-9">{{ $product->updated_at->format('d/m/Y H:i:s') }}</dd>
            </dl>
            {{-- Botões de Ação com verificação de Policy --}}
            @can('update', $product)
                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning"><i class="fas fa-edit"></i> Editar</a>
            @endcan

            @can('delete', $product)
                <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja excluir este produto? Esta ação não pode ser desfeita.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> Excluir</button>
                </form>
            @endcan
            <a href="{{ route('products.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Voltar para a Lista</a>
        </div>
    </div>
@stop