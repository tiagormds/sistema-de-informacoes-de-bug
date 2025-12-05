<?php

namespace App\Http\Controllers;

use App\Http\Requests\MoveTaskRequest;
use App\Http\Requests\StoreTaskRequest;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

/**
 *
 */
class TaskController extends Controller
{

    /**
     * @var TaskService
     */
    protected $taskService;

    /**
     * @param TaskService $taskService
     */
    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    /**
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('Kanban/Index', [
            'tasks' => Auth::user()->tasks()->orderBy('order')->get()
        ]);
    }

    /**
     * @param StoreTaskRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreTaskRequest $request)
    {
        $this->taskService->createTask($request->user(), $request->validated());

        // Com Inertia, o redirect volta pra mesma página e atualiza a lista sozinha!
        return redirect()->back();
    }

    /**
     * @param MoveTaskRequest $request
     * @param Task $task
     * @return \Illuminate\Http\JsonResponse
     */
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
