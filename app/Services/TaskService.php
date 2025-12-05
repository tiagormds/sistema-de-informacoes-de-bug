<?php

namespace App\Services;

use App\Models\Task;
use App\Enums\TaskStatus;

class TaskService
{
    // ... métodos create, etc...

    /**
     * Atualiza o status e a ordem da tarefa
     */
    public function moveTask(Task $task, string $status, int $newOrder): void
    {
        $task->update([
            'status' => TaskStatus::from($status), // Converte string para Enum
            'order' => $newOrder,
        ]);
    }
}
