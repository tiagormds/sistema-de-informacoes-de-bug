<?php

namespace App\Http\Controllers;

use App\Models\Bug;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class BugController extends Controller
{
    public function index()
    {
        return Inertia::render('Bugs/Index', [
            'bugs' => Auth::user()->bugs()->latest()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'platform' => 'nullable|string|max:50', // ex: Docker, Git
            'error_message' => 'nullable|string',
            'solution' => 'required|string',
        ]);

        $request->user()->bugs()->create($validated);

        return Redirect::route('bugs.index');

    }

    public function destroy(Bug $bug)
    {
        if ($bug->user_id !== Auth::id()) {
            abort(403);
        }

        $bug->delete();

        return Redirect::route('bugs.index');
    }
}
