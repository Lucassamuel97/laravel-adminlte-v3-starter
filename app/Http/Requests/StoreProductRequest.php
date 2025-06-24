<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check(); // Apenas usuários autenticados podem criar
    }


    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O campo Nome do Produto é obrigatório.',
            'name.max' => 'O Nome do Produto não pode ter mais que :max caracteres.',
            'price.required' => 'O campo Preço é obrigatório.',
            'price.numeric' => 'O Preço deve ser um valor numérico.',
            'price.min' => 'O Preço não pode ser negativo.',
            'stock.required' => 'O campo Estoque é obrigatório.',
            'stock.integer' => 'O Estoque deve ser um número inteiro.',
            'stock.min' => 'O Estoque não pode ser negativo.',
        ];
    }
}