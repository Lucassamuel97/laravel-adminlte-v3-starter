<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{

    public function viewAny(User $user): bool
    {
        return true; // Todos os usuários autenticados podem ver a lista
    }


    public function view(User $user, Product $product): bool
    {
        return true; // Todos os usuários autenticados podem ver um produto específico
    }


    public function create(User $user): bool
    {
        return true; // Todos os usuários autenticados podem criar produtos
    }


    public function update(User $user, Product $product): bool
    {
        // Apenas o usuário que criou o produto pode atualizá-lo
        return $user->id === $product->user_id;
    }


    public function delete(User $user, Product $product): bool
    {
        // Apenas o usuário que criou o produto pode excluí-lo
        return $user->id === $product->user_id;
    }


    public function restore(User $user, Product $product): bool
    {
        // Não estamos usando soft deletes, mas se estivesse, a lógica seria aqui.
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Product $product): bool
    {
        // Não estamos usando soft deletes, mas se estivesse, a lógica seria aqui.
        return false;
    }
}
