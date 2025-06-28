<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nome' => 'required',
            'email' => 'required|email|unique:customers',
            'telefone' => 'required',
            'endereco' => 'required',
            'cpf' => 'required|unique:customers',
            'rg' => 'required|unique:customers',
            'data_nascimento' => 'required|date',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'telefone' => preg_replace('/[^0-9]/', '', $this->telefone),
            'cpf' => preg_replace('/[^0-9]/', '', $this->cpf),
            'rg' => preg_replace('/[^0-9]/', '', $this->rg),
            'data_nascimento' => $this->data_nascimento ? \Carbon\Carbon::parse($this->data_nascimento)->format('Y-m-d') : null,
        ]);
    }
}
