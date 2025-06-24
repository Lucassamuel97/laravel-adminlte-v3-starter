<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        // A autorização é feita via Policy no Controller (__construct)
        return Auth::check();
    }

    public function rules(): array
    {
        $userId = $this->route('user')->id; // Pega o ID do usuário da rota

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $userId], // Unique ignorando o próprio ID
            'password' => ['nullable', 'confirmed', Password::defaults()], // Senha opcional no update
            'role' => ['required', 'string', 'in:admin,operador'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome é obrigatório.',
            'email.required' => 'O email é obrigatório.',
            'email.email' => 'Formato de email inválido.',
            'email.unique' => 'Este email já está cadastrado por outro usuário.',
            'password.confirmed' => 'A confirmação de senha não confere.',
            'role.required' => 'O nível de acesso é obrigatório.',
            'role.in' => 'O nível de acesso deve ser Admin ou Operador.',
        ];
    }
}