<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTodoRequest;
use App\Http\Requests\UpdateTodoRequest;
use App\Http\Resources\TodoResource;
use App\Models\Todo;
use App\Services\TodoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TodoController extends Controller
{
    protected TodoService $todoService;

    public function __construct(TodoService $todoService)
    {
        $this->todoService = $todoService;
    }

    public function index(Request $request): JsonResponse
    {
        $todos = $this->todoService->getUserTodos($request->user()->id);
        $counts = $this->todoService->countTodosByStatus($request->user()->id);

        return response()->json([
            'success' => true,
            'data' => TodoResource::collection($todos),
            'meta' => $counts
        ]);
    }

    public function store(StoreTodoRequest $request): JsonResponse
    {
        try {
            $todo = $this->todoService->createTodo(
                $request->user()->id,
                $request->validated()
            );

            return response()->json([
                'success' => true,
                'message' => 'Todo created successfully.',
                'data' => new TodoResource($todo)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create todo.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Request $request, Todo $todo): JsonResponse
    {
        try {
            // Vérification que le todo appartient à l'utilisateur
            if ($todo->user_id !== $request->user()->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized.'
                ], 403);
            }

            return response()->json([
                'success' => true,
                'data' => new TodoResource($todo)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Todo not found.'
            ], 404);
        }
    }

    public function update(UpdateTodoRequest $request, Todo $todo): JsonResponse
    {
        try {
            $updatedTodo = $this->todoService->updateTodo(
                $todo->id,
                $request->user()->id,
                $request->validated()
            );

            return response()->json([
                'success' => true,
                'message' => 'Todo updated successfully.',
                'data' => new TodoResource($updatedTodo)
            ]);
        } catch (NotFoundHttpException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update todo.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request, Todo $todo): JsonResponse
    {
        try {
            $this->todoService->deleteTodo($todo->id, $request->user()->id);

            return response()->json([
                'success' => true,
                'message' => 'Todo deleted successfully.'
            ]);
        } catch (NotFoundHttpException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete todo.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function completed(Request $request): JsonResponse
    {
        $todos = $this->todoService->getCompletedTodos($request->user()->id);

        return response()->json([
            'success' => true,
            'data' => TodoResource::collection($todos)
        ]);
    }

    public function pending(Request $request): JsonResponse
    {
        $todos = $this->todoService->getPendingTodos($request->user()->id);

        return response()->json([
            'success' => true,
            'data' => TodoResource::collection($todos)
        ]);
    }
}