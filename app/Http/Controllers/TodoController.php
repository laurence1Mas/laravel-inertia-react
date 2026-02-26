<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTodoRequest;
use App\Http\Requests\UpdateTodoRequest;
use App\Services\TodoService;
use Inertia\Inertia;
use App\Models\Todo;
use App\CustomData\Todo\CreateTodoData;
use App\CustomData\Todo\UpdateTodoData;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TodoController extends Controller
{
    protected TodoService $todoService;

    public function __construct(TodoService $todoService)
    {
        $this->todoService = $todoService;
    }

    public function index()
    {
        $todos = $this->todoService->getUserTodos(auth()->id());
        $counts = $this->todoService->countTodosByStatus(auth()->id());

        return Inertia::render('Todos/Index', [
            'todos' => $todos,
            'counts' => $counts,
            'flash' => [
                'success' => session('success'),
                'error' => session('error'),
            ]
        ]);
    }

    public function store(StoreTodoRequest $request)
    {
        try {
            $data = CreateTodoData::make(
                $request->validated()
            );

            $this->todoService->createTodo(
                auth()->id(),
                $data
            );

            return redirect()->back()
                ->with('success', 'Todo créé avec succès.');

        } catch (\Exception $e) {

            return redirect()->back()
                ->with('error', 'Erreur lors de la création du todo.')
                ->withInput();
        }
    }

    public function update(UpdateTodoRequest $request, Todo $todo)
    {
        try {

            $data = UpdateTodoData::make(
                $request->validated()
            );

            $this->todoService->updateTodo(
                $todo->id,
                auth()->id(),
                $data
            );

            return redirect()->back()
                ->with('success', 'Todo mis à jour avec succès.');

        } catch (NotFoundHttpException $e) {

            return redirect()->back()
                ->with('error', $e->getMessage());

        } catch (\Exception $e) {

            return redirect()->back()
                ->with('error', 'Erreur lors de la mise à jour du todo.');
        }
    }

    public function destroy(Todo $todo)
    {
        try {
            $this->todoService->deleteTodo($todo->id, auth()->id());

            return redirect()->back()->with('success', 'Todo supprimé avec succès.');
        } catch (NotFoundHttpException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression du todo.');
        }
    }

    public function completed()
    {
        $todos = $this->todoService->getCompletedTodos(auth()->id());

        return Inertia::render('Todos/Completed', [
            'todos' => $todos,
        ]);
    }

    public function pending()
    {
        $todos = $this->todoService->getPendingTodos(auth()->id());

        return Inertia::render('Todos/Pending', [
            'todos' => $todos,
        ]);
    }
}
