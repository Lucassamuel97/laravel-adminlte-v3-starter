@extends('layouts.app')

@section('title', 'Detalhes do Cliente')

@section('content_header_title', 'Detalhes do Cliente')
@section('content_header_subtitle',  $customer->nome ?? 'Cliente')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Informações do Cliente</h3>
        </div>
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">ID:</dt>
                <dd class="col-sm-9">{{ $customer->id }}</dd>

                <dt class="col-sm-3">Nome:</dt>
                <dd class="col-sm-9">{{ $customer->nome }}</dd>

                <dt class="col-sm-3">Email:</dt>
                <dd class="col-sm-9">{{ $customer->email }}</dd>

                <dt class="col-sm-3">Telefone:</dt>
                <dd class="col-sm-9">{{ $customer->telefone }}</dd>

                <dt class="col-sm-3">Endereço:</dt>
                <dd class="col-sm-9">{{ $customer->endereco }}</dd>

                <dt class="col-sm-3">CPF:</dt>
                <dd class="col-sm-9">{{ $customer->cpf }}</dd>

                <dt class="col-sm-3">RG:</dt>
                <dd class="col-sm-9">{{ $customer->rg }}</dd>

                <dt class="col-sm-3">Data de Nascimento:</dt>
                <dd class="col-sm-9">{{ $customer->data_nascimento }}</dd>

                <dt class="col-sm-3">Criado em:</dt>
                <dd class="col-sm-9">{{ $customer->created_at->format('d/m/Y H:i:s') }}</dd>

                <dt class="col-sm-3">Atualizado em:</dt>
                <dd class="col-sm-9">{{ $customer->updated_at->format('d/m/Y H:i:s') }}</dd>
            </dl>
            @can('update', $customer)
            <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-warning"><i class="fas fa-edit"></i> Editar</a>
            @endcan
            @can('delete', $customer)
            <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja excluir este cliente? Esta ação não pode ser desfeita.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> Excluir</button>
            </form>
            @endcan
            <a href="{{ route('customers.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Voltar para a Lista</a>
        </div>
    </div>
@stop