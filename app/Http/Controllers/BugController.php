<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBugRequest;
use App\Http\Requests\UpdateBugRequest;
use App\Models\Bug;
use App\Models\Platform;
use App\Services\BugService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class BugController extends Controller
{

    protected $bugService;
    public function __construct(BugService $bugService)
    {
        $this->bugService = $bugService;
    }

    public function index()
    {
        return Inertia::render('Bugs/Index', [
            'bugs' => Auth::user()->bugs()->with('platform')->latest()->get(),
            'platforms' => Platform::all(),
        ]);
    }

    public function store(StoreBugRequest $request)
    {
       $this->bugService->createBug($request->user(), $request->validated());

        return Redirect::route('bugs.index');

    }

    public function edit(Bug $bug, Platform $platform)
    {
        if ($bug->user_id !== Auth::id()) {
            abort(403);
        }

        return Inertia::render('Bugs/Edit', ['bug' => $bug, 'platforms' => $platform]);
    }

    public function update(UpdateBugRequest $request, Bug $bug)
    {
        if ($bug->user_id !== Auth::id()) {
            abort(403);
        }

        $this->bugService->updateBug($bug, $request->validated());

        return redirect(route('bugs.index'));

    }

    public function destroy(Bug $bug)
    {
        if ($bug->user_id !== Auth::id()) {
            abort(403);
        }

        $this->bugService->deleteBug($bug);

        return Redirect::route('bugs.index');
    }
}
