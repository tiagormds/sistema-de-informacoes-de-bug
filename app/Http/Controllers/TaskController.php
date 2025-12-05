<?php

namespace App\Http\Controllers;

use App\Http\Requests\MoveTaskRequest;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class TaskController extends Controller
{

    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index()
    {
        return Inertia::render('Kanban/Index', [
            'tasks' => Auth::user()->tasks()->orderBy('order')->get()
        ]);
    }

    public function move(MoveTaskRequest $request, Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403);
        }

        $this->taskService->moveTask($task, $request->status, $request->newPosition);

        // Retorna sucesso (não precisa redirecionar com Inertia aqui para não piscar a tela)
        return response()->json(['message' => 'Moved!']);
    }
}
