<?php

namespace App\Services;

use App\Models\Task;
use App\Enums\TaskStatus;
use App\Models\User;

/**
 *
 */
class TaskService
{

    /**
     * @param Task $task
     * @param string $status
     * @param int $newOrder
     * @return void
     */
    public function moveTask(Task $task, string $status, int $newOrder): void
    {
        $task->update([
            'status' => TaskStatus::from($status), // Converte string para Enum
            'order' => $newOrder,
        ]);
    }

    /**
     * @param User $user
     * @param array $data
     * @return Task
     */
    public function createTask(User $user, array $data): Task
    {
        // Define valores padrão
        $data['status'] = TaskStatus::PENDING; // Sempre começa em "A Fazer"

        // Pega a maior ordem atual pra colocar no final da fila
        $maxOrder = $user->tasks()->where('status', TaskStatus::PENDING)->max('order');
        $data['order'] = $maxOrder ? $maxOrder + 1 : 0;

        return $user->tasks()->create($data);
    }
}
