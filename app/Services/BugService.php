<?php

namespace App\Services;

use App\Models\Bug;
use App\Models\User;

class BugService
{
    /**
     * Cria um novo bug vinculado ao usuário.
     */
    public function createBug(User $user, array $data): Bug
    {
        return $user->bugs()->create($data);
    }

    /**
     * Atualiza um bug existente.
     */
    public function updateBug(Bug $bug, array $data): bool
    {
        return $bug->update($data);
    }

    /**
     * Deleta um bug.
     */
    public function deleteBug(Bug $bug): ?bool
    {
        return $bug->delete();
    }
}
