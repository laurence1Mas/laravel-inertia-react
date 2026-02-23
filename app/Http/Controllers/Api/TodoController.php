<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function index(Request $request)
    {
        $todos = Todo::where('user_id', $request->user()->id)
                     ->orderBy('created_at', 'desc')
                     ->get();

        return response()->json([
            'success' => true,
            'data' => $todos
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $todo = $request->user()->todos()->create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Todo created successfully',
            'data' => $todo
        ], 201);
    }

    public function show(Request $request, Todo $todo)
    {
        if ($todo->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $todo
        ]);
    }

    public function update(Request $request, Todo $todo)
    {
        if ($todo->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'completed' => 'sometimes|boolean',
        ]);

        $todo->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Todo updated successfully',
            'data' => $todo
        ]);
    }

    public function destroy(Request $request, Todo $todo)
    {
        if ($todo->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $todo->delete();

        return response()->json([
            'success' => true,
            'message' => 'Todo deleted successfully'
        ]);
    }
}
