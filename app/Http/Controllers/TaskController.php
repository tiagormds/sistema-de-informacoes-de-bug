<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class TaskController extends Controller
{
    public function index()
    {
        return Inertia::render('Kanban/Index', [
            'tasks' => Auth::user()->tasks()->orderBy('order')->get()
        ]);
    }
}
