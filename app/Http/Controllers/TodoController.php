<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TodoController extends Controller
{
    public function index()
    {
        $todos = Todo::where('user_id', auth()->id())
                     ->orderBy('created_at', 'desc')
                     ->get();

        return Inertia::render('Todos/Index', [
            'todos' => $todos,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $todo = auth()->user()->todos()->create($validated);

        return redirect()->back()->with('success', 'Todo créé avec succès.');
    }

    public function update(Request $request, Todo $todo)
    {
        $this->authorize('update', $todo);

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'completed' => 'sometimes|boolean',
        ]);

        $todo->update($validated);

        return redirect()->back()->with('success', 'Todo mis à jour.');
    }

    public function destroy(Todo $todo)
    {
        $this->authorize('delete', $todo);

        $todo->delete();

        return redirect()->back()->with('success', 'Todo supprimé.');
    }
}
